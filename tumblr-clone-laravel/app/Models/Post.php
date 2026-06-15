<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'body', 'media_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedByAuthUser(): bool
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    public function scopeFeedForUser($query, int $userId)
    {
        return $query->whereIn('user_id', function ($q) use ($userId) {
                $q->select('followed_id')
                  ->from('follows')
                  ->where('follower_id', $userId);
            })
            ->orWhere('user_id', $userId)
            ->withCount('likes')
            ->with('user')
            ->latest();
    }

    public function scopeByUser($query, int $profileUserId, int $currentUserId)
    {
        return $query->where('user_id', $profileUserId)
            ->withCount('likes')
            ->with('user')
            ->latest();
    }

    public function belongsToUser(int $userId): bool
    {
        return $this->user_id === $userId;
    }
}
