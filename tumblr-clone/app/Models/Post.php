<?php

class Post extends Model
{
    public function create(int $userId, string $type, ?string $title, ?string $body, ?string $mediaUrl): int
    {
        return $this->insert(
            'INSERT INTO posts (user_id, type, title, body, media_url) VALUES (?, ?, ?, ?, ?)',
            [$userId, $type, $title, $body, $mediaUrl]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->fetch(
            'SELECT p.*, u.username, u.avatar
             FROM posts p
             JOIN users u ON u.id = p.user_id
             WHERE p.id = ?',
            [$id]
        );
    }

    public function getFeed(int $userId, int $limit = 20, int $offset = 0): array
    {
        return $this->fetchAll(
            'SELECT p.*, u.username, u.avatar,
                    (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
                    (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = ?) AS liked
             FROM posts p
             JOIN users u ON u.id = p.user_id
             WHERE p.user_id IN (
                 SELECT followed_id FROM follows WHERE follower_id = ?
             ) OR p.user_id = ?
             ORDER BY p.created_at DESC
             LIMIT ? OFFSET ?',
            [$userId, $userId, $userId, $limit, $offset]
        );
    }

    public function getByUser(int $profileUserId, int $currentUserId, int $limit = 20, int $offset = 0): array
    {
        return $this->fetchAll(
            'SELECT p.*, u.username, u.avatar,
                    (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
                    (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = ?) AS liked
             FROM posts p
             JOIN users u ON u.id = p.user_id
             WHERE p.user_id = ?
             ORDER BY p.created_at DESC
             LIMIT ? OFFSET ?',
            [$currentUserId, $profileUserId, $limit, $offset]
        );
    }

    public function update(int $postId, string $type, ?string $title, ?string $body, ?string $mediaUrl): void
    {
        $this->query(
            'UPDATE posts SET type = ?, title = ?, body = ?, media_url = ? WHERE id = ?',
            [$type, $title, $body, $mediaUrl, $postId]
        );
    }

    public function delete(int $postId): void
    {
        $this->query('DELETE FROM posts WHERE id = ?', [$postId]);
    }

    public function belongsToUser(int $postId, int $userId): bool
    {
        $row = $this->fetch(
            'SELECT 1 FROM posts WHERE id = ? AND user_id = ?',
            [$postId, $userId]
        );
        return $row !== null;
    }
}
