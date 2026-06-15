<article class="post-card post-card--<?= $post['type'] ?>" data-post-id="<?= $post['id'] ?>">

    <div class="post-card-header">
        <a href="<?= BASE_URL ?>/profile/<?= $post['username'] ?>" class="post-card-author">
            <img src="<?= $post['avatar'] ? BASE_URL . '/' . $post['avatar'] : DEFAULT_AVATAR ?>"
                 alt="avatar" class="avatar avatar--sm">
            <span><?= htmlspecialchars($post['username']) ?></span>
        </a>
        <small class="post-card-date">
            <?= date('d M Y, H:i', strtotime($post['created_at'])) ?>
        </small>
    </div>

    <?php if ($post['type'] === 'image' && $post['media_url']): ?>
        <div class="post-card-media">
            <img src="<?= BASE_URL . '/' . $post['media_url'] ?>" alt="Post media">
        </div>
    <?php endif; ?>

    <?php if ($post['title']): ?>
        <h2 class="post-card-title"><?= htmlspecialchars($post['title']) ?></h2>
    <?php endif; ?>

    <?php if ($post['type'] === 'quote'): ?>
        <blockquote class="post-card-quote">
            <?= nl2br(htmlspecialchars($post['body'])) ?>
        </blockquote>
    <?php elseif ($post['type'] === 'link' && $post['media_url']): ?>
        <a href="<?= htmlspecialchars($post['media_url']) ?>" target="_blank" rel="noopener" class="post-card-link">
            <?= htmlspecialchars($post['title'] ?: $post['media_url']) ?>
        </a>
    <?php elseif ($post['body']): ?>
        <div class="post-card-body">
            <?= nl2br(htmlspecialchars($post['body'])) ?>
        </div>
    <?php endif; ?>

    <div class="post-card-actions">
        <button class="btn-like <?= $post['liked'] ? 'liked' : '' ?>"
                data-post-id="<?= $post['id'] ?>">
            &#9829; <span class="like-count"><?= $post['like_count'] ?></span>
        </button>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === (int)$post['user_id']): ?>
            <a href="<?= BASE_URL ?>/edit?id=<?= $post['id'] ?>" class="btn btn-sm">Edit</a>
            <a href="<?= BASE_URL ?>/delete?id=<?= $post['id'] ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Ștergi postarea?')">Șterge</a>
        <?php endif; ?>
    </div>

</article>
