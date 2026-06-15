<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'type'  => 'required|in:text,image,quote,link',
                'title' => 'nullable|string|max:255',
                'body'  => 'nullable|string',
                'media' => 'nullable|image|max:2048',
            ]);

            $mediaUrl = null;

            if (in_array($data['type'], ['image', 'link']) && $request->hasFile('media')) {
                $mediaUrl = $request->file('media')->store('uploads', 'public');
            }

            auth()->user()->posts()->create([
                'type'      => $data['type'],
                'title'     => $data['title'],
                'body'      => $data['body'],
                'media_url' => $mediaUrl,
            ]);

            return redirect()->route('dashboard');
        }

        return view('posts.create');
    }

    public function edit(Request $request)
    {
        $post = Post::findOrFail((int) $request->query('id'));

        if (!$post->belongsToUser(auth()->id())) {
            return redirect()->route('dashboard');
        }

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'type'  => 'required|in:text,image,quote,link',
                'title' => 'nullable|string|max:255',
                'body'  => 'nullable|string',
                'media' => 'nullable|image|max:2048',
            ]);

            $mediaUrl = $post->media_url;

            if ($request->hasFile('media')) {
                $mediaUrl = $request->file('media')->store('uploads', 'public');
            }

            $post->update([
                'type'      => $data['type'],
                'title'     => $data['title'],
                'body'      => $data['body'],
                'media_url' => $mediaUrl,
            ]);

            return redirect()->route('profile', auth()->user()->username);
        }

        return view('posts.edit', compact('post'));
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

        return response()->json(
            Like::toggle(auth()->id(), (int) $request->post_id)
        );
    }
}
