<div class="reblog-overlay" id="reblog-overlay" style="display:none;">
    <div class="reblog-modal">
        <button class="reblog-close" onclick="closeReblogModal()">&times;</button>
        <h3>Distribuie postarea</h3>
        <form method="POST" action="{{ route('posts.reblog') }}">
            @csrf
            <input type="hidden" name="parent_post_id" id="reblog-post-id">
            <div class="reblog-preview" id="reblog-preview"></div>
            <label for="reblog-comment">Adaugă un comentariu (opțional)</label>
            <textarea name="comment" id="reblog-comment" rows="3" placeholder="Spune ceva..."></textarea>
            <button type="submit" class="btn btn-primary btn-block">Distribuie</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openReblogModal(postId, authorName, postBody) {
    document.getElementById('reblog-post-id').value = postId;
    document.getElementById('reblog-preview').innerHTML =
        '<small style="color:var(--text-muted);">Postare de @' + authorName + '</small>' +
        '<p style="margin-top:4px;font-size:0.9rem;color:var(--text-secondary);">' +
        (postBody ? postBody.substring(0, 200) : '(fără conținut)') + '</p>';
    document.getElementById('reblog-overlay').style.display = 'flex';
}

function closeReblogModal() {
    document.getElementById('reblog-overlay').style.display = 'none';
}

document.getElementById('reblog-overlay')?.addEventListener('click', function (e) {
    if (e.target === this) closeReblogModal();
});
</script>
@endpush
