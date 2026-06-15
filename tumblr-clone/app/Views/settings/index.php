<div class="settings">
    <h1>Setări</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <section class="settings-section">
        <h2>Avatar</h2>
        <img src="<?= $user['avatar'] ? BASE_URL . '/' . $user['avatar'] : DEFAULT_AVATAR ?>"
             alt="avatar" class="avatar avatar--lg">
        <form method="POST" action="<?= BASE_URL ?>/settings/avatar" enctype="multipart/form-data">
            <input type="file" name="avatar" accept="image/*" required>
            <button type="submit" class="btn btn-primary">Încarcă</button>
        </form>
    </section>

    <section class="settings-section">
        <h2>Bio</h2>
        <form method="POST" action="<?= BASE_URL ?>/settings">
            <textarea name="bio" rows="4" maxlength="500"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            <button type="submit" class="btn btn-primary">Salvează bio</button>
        </form>
    </section>

    <section class="settings-section">
        <h2>Schimbă parola</h2>
        <form method="POST" action="<?= BASE_URL ?>/settings/password">
            <label for="current_password">Parola actuală</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">Parola nouă</label>
            <input type="password" name="new_password" id="new_password" required minlength="6">

            <label for="confirm_password">Confirmă parola nouă</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit" class="btn btn-primary">Schimbă parola</button>
        </form>
    </section>
</div>
