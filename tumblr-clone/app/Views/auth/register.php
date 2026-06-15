<div class="auth-form">
    <h1>Înregistrare</h1>

    <?php if (isset($error)): ?>
        <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/register">
        <label for="username">Nume utilizator</label>
        <input type="text" name="username" id="username" required minlength="3" maxlength="50">

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Parolă</label>
        <input type="password" name="password" id="password" required minlength="6">

        <label for="confirm_password">Confirmă parola</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <button type="submit" class="btn btn-primary btn-block">Înregistrare</button>
    </form>

    <p class="auth-alt">Ai deja cont? <a href="<?= BASE_URL ?>/login">Autentifică-te</a></p>
</div>
