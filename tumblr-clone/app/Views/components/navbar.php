<nav class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/dashboard" class="navbar-brand">Tumbleweed</a>

        <div class="navbar-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/create" class="btn btn-primary btn-sm">+ Postează</a>
                <a href="<?= BASE_URL ?>/profile/<?= $_SESSION['username'] ?>" class="nav-link">Profil</a>
                <a href="<?= BASE_URL ?>/settings" class="nav-link">Setări</a>
                <a href="<?= BASE_URL ?>/logout" class="nav-link">Ieșire</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="nav-link">Autentificare</a>
                <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-sm">Înregistrare</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
