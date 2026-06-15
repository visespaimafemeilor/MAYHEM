<?php

namespace App\Http\Controllers;

use App\Events\PostSaved;
use App\Helpers\ImageOptimizer;
use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Notifications\PostCommented;
use App\Notifications\PostLiked;
use App\Notifications\PostReblogged;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'type'      => 'required|in:text,image,quote,link',
                'title'     => 'nullable|string|max:255',
                'body'      => 'nullable|string',
                'media'     => 'nullable|image|max:10240',
                'media_url' => 'nullable|url|max:2048',
                'tags'      => 'nullable|string',
                'status'    => 'sometimes|in:draft,published',
            ]);

            $mediaUrl = null;

            if ($data['type'] === 'image' && $request->hasFile('media')) {
                $file = ImageOptimizer::optimize($request->file('media'));
                $mediaUrl = $file->store('uploads', 'public');
            } elseif ($data['type'] === 'link' && !empty($data['media_url'])) {
                $mediaUrl = $data['media_url'];
            }

            $post = auth()->user()->posts()->create([
                'type'      => $data['type'],
                'title'     => $data['title'],
                'body'      => $data['body'],
                'media_url' => $mediaUrl,
                'status'    => $data['status'] ?? 'published',
            ]);

            if (!empty($data['tags'])) {
                $tagIds = $this->syncTags($data['tags']);
                $post->tags()->sync($tagIds);
            }

            event(new PostSaved($post));

            return redirect()->route('dashboard');
        }

        $tags = Tag::orderBy('name')->get();
        return view('posts.create', compact('tags'));
    }

    public function edit(Request $request)
    {
        $post = Post::findOrFail((int) $request->query('id'));

        if (!$post->belongsToUser(auth()->id())) {
            return redirect()->route('dashboard');
        }

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'type'      => 'required|in:text,image,quote,link',
                'title'     => 'nullable|string|max:255',
                'body'      => 'nullable|string',
                'media'     => 'nullable|image|max:10240',
                'media_url' => 'nullable|url|max:2048',
                'tags'      => 'nullable|string',
                'status'    => 'sometimes|in:draft,published',
            ]);

            $mediaUrl = $post->media_url;

            if ($data['type'] === 'image') {
                if ($request->hasFile('media')) {
                    $file = ImageOptimizer::optimize($request->file('media'));
                    $mediaUrl = $file->store('uploads', 'public');
                }
            } elseif ($data['type'] === 'link') {
                $mediaUrl = $data['media_url'] ?? $post->media_url;
            } else {
                $mediaUrl = null;
            }

            $post->update([
                'type'      => $data['type'],
                'title'     => $data['title'],
                'body'      => $data['body'],
                'media_url' => $mediaUrl,
                'status'    => $data['status'] ?? $post->status,
            ]);

            if (isset($data['tags'])) {
                $tagIds = $this->syncTags($data['tags']);
                $post->tags()->sync($tagIds);
            }

            event(new PostSaved($post));

            return redirect()->route('profile', auth()->user()->username);
        }

        $tags = Tag::orderBy('name')->get();
        $postTags = $post->tags->pluck('name')->implode(', ');
        return view('posts.edit', compact('post', 'tags', 'postTags'));
    }

    public function show(Post $post)
    {
        if ($post->status !== 'published' && !auth()->user()?->belongsToUser($post->user_id)) {
            abort(404);
        }

        $post->loadCount('likes')->load('user', 'tags', 'parentPost.user', 'comments.user');
        $post->liked = $post->likedByAuthUser();

        return view('posts.show', compact('post'));
    }

    public function comment(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'body'    => 'required|string|max:1000',
        ]);

        $post = Post::findOrFail((int) $data['post_id']);

        if ($post->status !== 'published') {
            return redirect()->back()->with('error', 'Postarea nu acceptă comentarii.');
        }

        $comment = auth()->user()->comments()->create([
            'post_id' => $post->id,
            'body'    => $data['body'],
        ]);

        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new PostCommented($post, auth()->user(), $comment));
        }

        return redirect()->route('posts.show', $post->id)
            ->withFragment('comments');
    }

    public function delete(Request $request)
    {
        $post = Post::findOrFail((int) $request->query('id'));

        if ($post->belongsToUser(auth()->id())) {
            $post->delete();
        }

        return redirect()->route('profile', auth()->user()->username);
    }

    public function like(Request $request)
    {
        $request->validate(['post_id' => 'required|exists:posts,id']);

        $postId = (int) $request->post_id;
        $result = Like::toggle(auth()->id(), $postId);

        if ($result['liked']) {
            $post = Post::find($postId);
            if ($post && $post->user_id !== auth()->id()) {
                $post->user->notify(new PostLiked($post, auth()->user()));
            }
        }

        return response()->json($result);
    }

    public function reblogForm(Post $post)
    {
        if ($post->status !== 'published') {
            return redirect()->route('dashboard')->with('error', 'Postarea nu mai este disponibilă.');
        }

        $post->load('user', 'tags');
        return view('posts.reblog', compact('post'));
    }

    public function reblog(Request $request)
    {
        $request->validate([
            'parent_post_id' => 'required|exists:posts,id',
            'comment'        => 'nullable|string|max:1000',
        ]);

        $original = Post::findOrFail((int) $request->parent_post_id);

        if ($original->status !== 'published') {
            return redirect()->back()->with('error', 'Postarea originală nu este disponibilă.');
        }

        $body = $request->comment;

        $post = auth()->user()->posts()->create([
            'type'           => $original->type,
            'title'          => $original->title,
            'body'           => $body,
            'media_url'      => $original->media_url,
            'status'         => 'published',
            'parent_post_id' => $original->id,
        ]);

        $tagIds = $original->tags->pluck('id')->toArray();
        $post->tags()->sync($tagIds);

        if ($original->user_id !== auth()->id()) {
            $original->user->notify(new PostReblogged($original, auth()->user()));
        }

        return redirect()->route('dashboard')->with('success', 'Postare distribuită.');
    }

    private function syncTags(string $tagsString): array
    {
        $names = array_filter(array_map('trim', explode(',', $tagsString)));

        return collect($names)->map(function ($name) {
            return Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            )->id;
        })->toArray();
    }
}
