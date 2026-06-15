<?php

class Follow extends Model
{
    public function toggle(int $followerId, int $followedId): array
    {
        $existing = $this->fetch(
            'SELECT id FROM follows WHERE follower_id = ? AND followed_id = ?',
            [$followerId, $followedId]
        );

        if ($existing) {
            $this->query('DELETE FROM follows WHERE id = ?', [$existing['id']]);
            $following = false;
        } else {
            $this->insert(
                'INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)',
                [$followerId, $followedId]
            );
            $following = true;
        }

        $count = $this->fetch(
            'SELECT COUNT(*) AS cnt FROM follows WHERE followed_id = ?',
            [$followedId]
        );

        return ['following' => $following, 'count' => (int) $count['cnt']];
    }

    public function isFollowing(int $followerId, int $followedId): bool
    {
        $row = $this->fetch(
            'SELECT 1 FROM follows WHERE follower_id = ? AND followed_id = ?',
            [$followerId, $followedId]
        );
        return $row !== null;
    }
}
