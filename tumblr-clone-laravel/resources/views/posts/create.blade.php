@extends('layouts.app')

@section('content')
<div class="post-form">
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

        <div id="media-field" style="display:none;">
            <label for="media">Fișier media</label>
            <input type="file" name="media" id="media" accept="image/*">
        </div>

        <label for="tags">Tag-uri (separate prin virgulă)</label>
        <input type="text" name="tags" id="tags" value="{{ old('tags') }}" placeholder="ex: muzică, artă, coding">

        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="published">Publică</option>
            <option value="draft">Ciornă</option>
        </select>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary btn-block">Publică</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleMediaField(type) {
    document.getElementById('media-field').style.display =
        (type === 'image' || type === 'link') ? 'block' : 'none';
}
</script>
@endpush
