<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = [
        'follower_id', 'followed_id',
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }

    public static function toggle(int $followerId, int $followedId): array
    {
        $follow = static::where('follower_id', $followerId)
            ->where('followed_id', $followedId)
            ->first();

        if ($follow) {
            $follow->delete();
            $following = false;
        } else {
            static::create(['follower_id' => $followerId, 'followed_id' => $followedId]);
            $following = true;
        }

        $count = static::where('followed_id', $followedId)->count();

        return ['following' => $following, 'count' => $count];
    }
}
