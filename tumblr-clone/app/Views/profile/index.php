<div class="profile">
    <div class="profile-header">
        <img src="<?= $profileUser['avatar'] ? BASE_URL . '/' . $profileUser['avatar'] : DEFAULT_AVATAR ?>"
             alt="avatar" class="avatar avatar--lg">
        <h1><?= htmlspecialchars($profileUser['username']) ?></h1>

        <?php if ($profileUser['bio']): ?>
            <p class="profile-bio"><?= nl2br(htmlspecialchars($profileUser['bio'])) ?></p>
        <?php endif; ?>

        <p class="profile-meta">
            Membru din <?= date('F Y', strtotime($profileUser['created_at'])) ?>
        </p>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== (int)$profileUser['id']): ?>
            <button class="btn btn-follow <?= $isFollowing ? 'following' : '' ?>"
                    data-user-id="<?= $profileUser['id'] ?>">
                <?= $isFollowing ? 'Urmărești' : 'Urmărește' ?>
            </button>
        <?php endif; ?>
    </div>

    <div class="profile-posts">
        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <p>Acest utilizator nu a postat încă nimic.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <?php require __DIR__ . '/../components/post_card.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
