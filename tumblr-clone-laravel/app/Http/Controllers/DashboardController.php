<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::feedForUser(auth()->id())
            ->get()
            ->each(function ($post) {
                $post->liked = $post->likedByAuthUser();
            });

        return view('dashboard', compact('posts'));
    }
}
