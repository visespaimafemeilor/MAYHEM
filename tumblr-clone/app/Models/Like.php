<?php

class Like extends Model
{
    public function toggle(int $userId, int $postId): array
    {
        $existing = $this->fetch(
            'SELECT id FROM likes WHERE user_id = ? AND post_id = ?',
            [$userId, $postId]
        );

        if ($existing) {
            $this->query('DELETE FROM likes WHERE id = ?', [$existing['id']]);
            $liked = false;
        } else {
            $this->insert(
                'INSERT INTO likes (user_id, post_id) VALUES (?, ?)',
                [$userId, $postId]
            );
            $liked = true;
        }

        $count = $this->fetch(
            'SELECT COUNT(*) AS cnt FROM likes WHERE post_id = ?',
            [$postId]
        );

        return ['liked' => $liked, 'count' => (int) $count['cnt']];
    }

    public function countByPost(int $postId): int
    {
        $row = $this->fetch(
            'SELECT COUNT(*) AS cnt FROM likes WHERE post_id = ?',
            [$postId]
        );
        return (int) $row['cnt'];
    }

    public function userLiked(int $userId, int $postId): bool
    {
        $row = $this->fetch(
            'SELECT 1 FROM likes WHERE user_id = ? AND post_id = ?',
            [$userId, $postId]
        );
        return $row !== null;
    }
}
