<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'body', 'media_url', 'status', 'parent_post_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function parentPost()
    {
        return $this->belongsTo(self::class, 'parent_post_id')
            ->with('user', 'tags');
    }

    public function rebloggedPosts()
    {
        return $this->hasMany(self::class, 'parent_post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function isReblog(): bool
    {
        return $this->parent_post_id !== null;
    }

    public function likedByAuthUser(): bool
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeFeedForUser($query, ?int $userId)
    {
        $base = $query->published()->withCount('likes')->with('user', 'tags', 'parentPost.user');

        if ($userId === null) {
            return $base->latest();
        }

        return $base->whereIn('user_id', function ($q) use ($userId) {
                $q->select('followed_id')
                  ->from('follows')
                  ->where('follower_id', $userId);
            })
            ->orWhere('user_id', $userId)
            ->latest();
    }

    public function scopeByUser($query, int $profileUserId)
    {
        return $query->published()->where('user_id', $profileUserId)
            ->withCount('likes')
            ->with('user', 'tags', 'parentPost.user')
            ->latest();
    }

    public function scopeSearch($query, string $term)
    {
        return $query->published()->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('body', 'like', "%{$term}%");
        });
    }

    public function scopeWithTag($query, string $slug)
    {
        return $query->published()->whereHas('tags', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    public function scopePopular($query, int $days = 7)
    {
        return $query->published()
            ->where('created_at', '>=', now()->subDays($days))
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->with('user', 'tags', 'parentPost.user');
    }

    public function belongsToUser(int $userId): bool
    {
        return $this->user_id === $userId;
    }
}
