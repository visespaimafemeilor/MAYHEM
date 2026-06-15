<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id', 'post_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public static function toggle(int $userId, int $postId): array
    {
        $like = static::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            static::create(['user_id' => $userId, 'post_id' => $postId]);
            $liked = true;
        }

        $count = static::where('post_id', $postId)->count();

        return ['liked' => $liked, 'count' => $count];
    }
}
