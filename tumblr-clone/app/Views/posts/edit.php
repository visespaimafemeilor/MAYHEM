<div class="post-form">
    <h1>Editează postarea</h1>

    <form method="POST" action="<?= BASE_URL ?>/edit?id=<?= $post['id'] ?>" enctype="multipart/form-data">
        <label for="type">Tip postare</label>
        <select name="type" id="type" onchange="toggleMediaField(this.value)">
            <option value="text"  <?= $post['type'] === 'text'  ? 'selected' : '' ?>>Text</option>
            <option value="image" <?= $post['type'] === 'image' ? 'selected' : '' ?>>Imagine</option>
            <option value="quote" <?= $post['type'] === 'quote' ? 'selected' : '' ?>>Citat</option>
            <option value="link"  <?= $post['type'] === 'link'  ? 'selected' : '' ?>>Link</option>
        </select>

        <label for="title">Titlu</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>" maxlength="255">

        <label for="body">Conținut</label>
        <textarea name="body" id="body" rows="6"><?= htmlspecialchars($post['body'] ?? '') ?></textarea>

        <div id="media-field" style="<?= in_array($post['type'], ['image','link']) ? 'block' : 'none' ?>">
            <label for="media">Înlocuiește fișierul media</label>
            <input type="file" name="media" id="media" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Salvează</button>
    </form>
</div>

<script>
function toggleMediaField(type) {
    document.getElementById('media-field').style.display =
        (type === 'image' || type === 'link') ? 'block' : 'none';
}
</script>
