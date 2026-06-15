@extends('layouts.app')

@section('content')
<div class="post-form">
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

        <div id="media-field" style="display:none;">
            <label for="media">Fișier media</label>
            <input type="file" name="media" id="media" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Salvează</button>
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
