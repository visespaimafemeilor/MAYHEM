<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    public Post $post;
    public User $commenter;
    public Comment $comment;

    public function __construct(Post $post, User $commenter, Comment $comment)
    {
        $this->post = $post;
        $this->commenter = $commenter;
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'comment',
            'post_id'       => $this->post->id,
            'commenter_id'  => $this->commenter->id,
            'commenter_name' => $this->commenter->username,
            'message'       => "{$this->commenter->username} a comentat la postarea ta.",
        ];
    }
}
