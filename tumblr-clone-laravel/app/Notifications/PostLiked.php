<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    public Post $post;
    public User $liker;

    public function __construct(Post $post, User $liker)
    {
        $this->post = $post;
        $this->liker = $liker;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'like',
            'post_id'   => $this->post->id,
            'liker_id'  => $this->liker->id,
            'liker_name' => $this->liker->username,
            'message'   => "{$this->liker->username} ți-a apreciat postarea.",
        ];
    }
}
