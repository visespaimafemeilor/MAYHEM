<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::feedForUser(auth()->id());

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('tag')) {
            $query->withTag($request->tag);
        }

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        $posts = $query->paginate(10)
            ->withQueryString()
            ->through(function ($post) {
                $post->liked = $post->likedByAuthUser();
                return $post;
            });

        $tags = Tag::orderBy('name')->get();

        return view('dashboard', compact('posts', 'tags'));
    }

    public function explore(Request $request)
    {
        $query = Post::popular(30);

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('tag')) {
            $query->withTag($request->tag);
        }

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        $posts = $query->paginate(10)
            ->withQueryString()
            ->through(function ($post) {
                $post->liked = $post->likedByAuthUser();
                return $post;
            });

        $tags = Tag::orderBy('name')->get();

        return view('explore', compact('posts', 'tags'));
    }
}
