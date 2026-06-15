<div class="auth-form">
    <h1>Autentificare</h1>

    <?php if (isset($error)): ?>
        <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/login">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required autocomplete="email">

        <label for="password">Parolă</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn btn-primary btn-block">Autentificare</button>
    </form>

    <p class="auth-alt">Nu ai cont? <a href="<?= BASE_URL ?>/register">Înregistrează-te</a></p>
</div>
