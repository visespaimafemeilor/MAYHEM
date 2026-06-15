<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(string $username)
    {
        $profileUser = User::where('username', $username)->firstOrFail();

        $currentUserId = auth()->id();
        $posts = Post::byUser($profileUser->id, $currentUserId)
            ->get()
            ->each(function ($post) {
                $post->liked = $post->likedByAuthUser();
            });

        $isFollowing = $currentUserId
            ? auth()->user()->isFollowing($profileUser)
            : false;

        return view('profile.index', compact('profileUser', 'posts', 'isFollowing'));
    }

    public function follow(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $followedId = (int) $request->user_id;

        if ($followedId === auth()->id()) {
            return response()->json(['error' => 'Nu te poți urmări pe tine însuți.']);
        }

        return response()->json(
            Follow::toggle(auth()->id(), $followedId)
        );
    }
}
