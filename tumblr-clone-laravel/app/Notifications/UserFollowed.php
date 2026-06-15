<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;

    public User $follower;

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'follow',
            'follower_id'   => $this->follower->id,
            'follower_name' => $this->follower->username,
            'message'       => "{$this->follower->username} a început să te urmărească.",
        ];
    }
}
