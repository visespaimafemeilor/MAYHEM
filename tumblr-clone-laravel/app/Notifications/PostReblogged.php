<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostReblogged extends Notification
{
    use Queueable;

    public Post $originalPost;
    public User $reblogger;

    public function __construct(Post $originalPost, User $reblogger)
    {
        $this->originalPost = $originalPost;
        $this->reblogger = $reblogger;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'reblog',
            'post_id'       => $this->originalPost->id,
            'reblogger_id'  => $this->reblogger->id,
            'reblogger_name' => $this->reblogger->username,
            'message'       => "{$this->reblogger->username} a distribuit postarea ta.",
        ];
    }
}
