<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name', 'slug',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
