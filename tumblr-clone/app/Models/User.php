<?php

class User extends Model
{
    public function create(string $username, string $email, string $passwordHash): int
    {
        return $this->insert(
            'INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)',
            [$username, $email, $passwordHash]
        );
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetch('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function findByUsername(string $username): ?array
    {
        return $this->fetch('SELECT * FROM users WHERE username = ?', [$username]);
    }

    public function findById(int $id): ?array
    {
        return $this->fetch('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public function updateAvatar(int $userId, string $avatar): void
    {
        $this->query('UPDATE users SET avatar = ? WHERE id = ?', [$avatar, $userId]);
    }

    public function updatePassword(int $userId, string $newHash): void
    {
        $this->query('UPDATE users SET password_hash = ? WHERE id = ?', [$newHash, $userId]);
    }

    public function updateBio(int $userId, string $bio): void
    {
        $this->query('UPDATE users SET bio = ? WHERE id = ?', [$bio, $userId]);
    }

    public function getFollowers(int $userId): array
    {
        return $this->fetchAll(
            'SELECT u.id, u.username, u.avatar FROM follows f
             JOIN users u ON u.id = f.follower_id
             WHERE f.followed_id = ?',
            [$userId]
        );
    }

    public function getFollowing(int $userId): array
    {
        return $this->fetchAll(
            'SELECT u.id, u.username, u.avatar FROM follows f
             JOIN users u ON u.id = f.followed_id
             WHERE f.follower_id = ?',
            [$userId]
        );
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
