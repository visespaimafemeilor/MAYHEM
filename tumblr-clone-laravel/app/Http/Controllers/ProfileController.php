<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use App\Notifications\UserFollowed;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(string $username)
    {
        $profileUser = User::where('username', $username)->firstOrFail();

        $currentUserId = auth()->id();
        $posts = Post::byUser($profileUser->id)
            ->paginate(10)
            ->through(function ($post) {
                $post->liked = $post->likedByAuthUser();
                return $post;
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
        $followerId = auth()->id();

        if ($followedId === $followerId) {
            return response()->json(['error' => 'Nu te poți urmări pe tine însuți.']);
        }

        $result = Follow::toggle($followerId, $followedId);

        if ($result['following']) {
            $followedUser = User::find($followedId);
            if ($followedUser) {
                $followedUser->notify(new UserFollowed(auth()->user()));
            }
        }

        return response()->json($result);
    }
}
