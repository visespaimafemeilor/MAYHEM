<div class="dashboard">
    <h1 class="page-title">Dashboard</h1>

    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <p>Nicio postare în feed. Începe să urmărești utilizatori sau <a href="<?= BASE_URL ?>/create">creează prima postare</a>.</p>
        </div>
    <?php else: ?>
        <div class="feed">
            <?php foreach ($posts as $post): ?>
                <?php require __DIR__ . '/../components/post_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
