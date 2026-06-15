<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    protected $fillable = [
        'username', 'email', 'password', 'avatar', 'bio', 'accent_color',
    ];

    protected $hidden = [
        'password',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id')
            ->withTimestamps();
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getAvatarAttribute(?string $value): string
    {
        return $value ?: asset('assets/images/default-avatar.svg');
    }
}
