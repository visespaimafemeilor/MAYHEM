-- ============================================================
-- Tumbleweed Blog - SQL Schema (MySQL / PostgreSQL)
-- Rulesază acest script în DBeaver pentru a crea baza de date
-- ============================================================

CREATE DATABASE IF NOT EXISTS tumbleweed
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tumbleweed;

-- -----------------------------------------------------------
-- Tabela: users
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    avatar     VARCHAR(255) DEFAULT NULL,
    bio        TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Tabela: posts
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS posts (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    type       ENUM('text','image','quote','link') NOT NULL DEFAULT 'text',
    title      VARCHAR(255) DEFAULT NULL,
    body       TEXT DEFAULT NULL,
    media_url  VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_posts_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_posts_user_id ON posts(user_id);
CREATE INDEX idx_posts_created  ON posts(created_at DESC);

-- -----------------------------------------------------------
-- Tabela: likes
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS likes (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    post_id    INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_likes_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_likes_post
        FOREIGN KEY (post_id) REFERENCES posts(id)
        ON DELETE CASCADE,

    UNIQUE KEY uq_likes (user_id, post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_likes_post ON likes(post_id);

-- -----------------------------------------------------------
-- Tabela: follows
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS follows (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    follower_id INT UNSIGNED NOT NULL,
    followed_id INT UNSIGNED NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_follows_follower
        FOREIGN KEY (follower_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_follows_followed
        FOREIGN KEY (followed_id) REFERENCES users(id)
        ON DELETE CASCADE,

    UNIQUE KEY uq_follows (follower_id, followed_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_follows_followed ON follows(followed_id);
