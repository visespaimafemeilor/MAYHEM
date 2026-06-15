@extends('layouts.app')

@section('content')
<div class="post-form">
    <a href="{{ route('dashboard') }}" class="back-link">Înapoi la Dashboard</a>
    <h1>Postare nouă</h1>

    <form method="POST" action="{{ route('posts.create') }}" enctype="multipart/form-data">
        @csrf

        <label for="type">Tip postare</label>
        <select name="type" id="type" onchange="toggleMediaField(this.value)">
            <option value="text">Text</option>
            <option value="image">Imagine</option>
            <option value="quote">Citat</option>
            <option value="link">Link</option>
        </select>

        <label for="title">Titlu</label>
        <input type="text" name="title" id="title" maxlength="255" value="{{ old('title') }}">

        <label for="body">Conținut</label>
        <textarea name="body" id="body" rows="6">{{ old('body') }}</textarea>

        <div id="media-file-field" style="display:none;">
            <label for="media">Imagine</label>
            <input type="file" name="media" id="media" accept="image/*" onchange="previewImage(this)">
            <div id="image-preview" style="margin-top:8px;max-width:100%;"></div>
        </div>

        <div id="media-url-field" style="display:none;">
            <label for="media_url">URL link</label>
            <input type="url" name="media_url" id="media_url" placeholder="https://..." value="{{ old('media_url') }}">
        </div>

        <label for="tags">Tag-uri (separate prin virgulă)</label>
        <input type="text" name="tags" id="tags" value="{{ old('tags') }}" placeholder="ex: muzică, artă, coding">

        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="published">Publică</option>
            <option value="draft">Ciornă</option>
        </select>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top:24px;">Publică</button>
    </form>

    <hr style="margin:32px 0;border-color:var(--border);">

    <div style="background:var(--bg-card);padding:20px;border:1px solid var(--border);">
        <h3 style="font-family:var(--font-display);font-style:italic;">🤖 Ghostwriter AI</h3>
        <p style="font-size:0.85rem;color:var(--text-secondary);margin-bottom:12px;">
            Scrie o idee scurtă și lasă AI-ul să genereze o postare pentru tine.
        </p>
        <input type="text" id="ai-idea" placeholder="ex: filozofie de duminică, gânduri despre cod..."
               maxlength="500"
               style="width:100%;padding:10px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-primary);font-family:var(--font-mono);margin-bottom:12px;">
        <div style="display:flex;gap:8px;align-items:center;">
            <button id="btn-ai-generate" class="btn btn-primary">Generează cu AI</button>
            <span id="ai-loading" style="display:none;font-size:0.8rem;color:var(--text-muted);">Se generează...</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleMediaField(type) {
    document.getElementById('media-file-field').style.display = type === 'image' ? 'block' : 'none';
    document.getElementById('media-url-field').style.display = type === 'link' ? 'block' : 'none';
}

function previewImage(input) {
    var preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        var img = document.createElement('img');
        img.src = URL.createObjectURL(input.files[0]);
        img.style.maxWidth = '100%';
        img.style.maxHeight = '300px';
        img.style.border = '1px solid var(--border)';
        preview.appendChild(img);
    }
}
</script>
@endpush
