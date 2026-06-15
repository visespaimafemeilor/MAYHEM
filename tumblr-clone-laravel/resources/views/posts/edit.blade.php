@extends('layouts.app')

@section('content')
<div class="post-form">
    <a href="{{ route('dashboard') }}" class="back-link">Înapoi la Dashboard</a>
    <h1>Editează postarea</h1>

    <form method="POST" action="{{ route('posts.edit', ['id' => $post->id]) }}" enctype="multipart/form-data">
        @csrf

        <label for="type">Tip postare</label>
        <select name="type" id="type" onchange="toggleMediaField(this.value)">
            <option value="text" @if($post->type === 'text') selected @endif>Text</option>
            <option value="image" @if($post->type === 'image') selected @endif>Imagine</option>
            <option value="quote" @if($post->type === 'quote') selected @endif>Citat</option>
            <option value="link" @if($post->type === 'link') selected @endif>Link</option>
        </select>

        <label for="title">Titlu</label>
        <input type="text" name="title" id="title" maxlength="255" value="{{ $post->title }}">

        <label for="body">Conținut</label>
        <textarea name="body" id="body" rows="6">{{ $post->body }}</textarea>

        <div id="media-file-field" style="display:{{ $post->type === 'image' ? 'block' : 'none' }};">
            <label for="media">Imagine</label>
            <input type="file" name="media" id="media" accept="image/*" onchange="previewImage(this)">
            <div id="image-preview" style="margin-top:8px;max-width:100%;">
                @if ($post->type === 'image' && $post->media_url)
                    <img src="{{ asset('storage/' . $post->media_url) }}" style="max-width:100%;max-height:300px;border:1px solid var(--border);">
                @endif
            </div>
        </div>

        <div id="media-url-field" style="display:{{ $post->type === 'link' ? 'block' : 'none' }};">
            <label for="media_url">URL link</label>
            <input type="url" name="media_url" id="media_url" placeholder="https://..." value="{{ $post->type === 'link' ? $post->media_url : '' }}">
        </div>

        <label for="tags">Tag-uri (separate prin virgulă)</label>
        <input type="text" name="tags" id="tags" value="{{ $postTags ?? '' }}">

        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="published" @if($post->status === 'published') selected @endif>Publică</option>
            <option value="draft" @if($post->status === 'draft') selected @endif>Ciornă</option>
        </select>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top:24px;">Salvează</button>
    </form>
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
