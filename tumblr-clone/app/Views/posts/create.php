<div class="post-form">
    <h1>Postare nouă</h1>

    <form method="POST" action="<?= BASE_URL ?>/create" enctype="multipart/form-data">
        <label for="type">Tip postare</label>
        <select name="type" id="type" onchange="toggleMediaField(this.value)">
            <option value="text">Text</option>
            <option value="image">Imagine</option>
            <option value="quote">Citat</option>
            <option value="link">Link</option>
        </select>

        <label for="title">Titlu</label>
        <input type="text" name="title" id="title" maxlength="255">

        <label for="body">Conținut</label>
        <textarea name="body" id="body" rows="6"></textarea>

        <div id="media-field" style="display:none;">
            <label for="media">Fișier media</label>
            <input type="file" name="media" id="media" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Publică</button>
    </form>
</div>

<script>
function toggleMediaField(type) {
    document.getElementById('media-field').style.display =
        (type === 'image' || type === 'link') ? 'block' : 'none';
}
</script>
