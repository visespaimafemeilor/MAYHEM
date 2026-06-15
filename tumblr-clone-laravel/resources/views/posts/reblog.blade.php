@extends('layouts.app')

@section('content')
<div class="post-form">
    <a href="{{ url()->previous() }}" class="back-link">&#8592; Înapoi</a>
    <h1>Distribuie postarea</h1>

    <div class="reblog-preview-card">
        <div class="post-card-header">
            <a href="{{ route('profile', $post->user->username) }}" class="post-card-author">
                <img src="{{ $post->user->avatar }}" alt="avatar" class="avatar avatar--sm">
                <span>{{ $post->user->username }}</span>
            </a>
            <small class="post-card-date">
                {{ $post->created_at->format('d M Y, H:i') }}
            </small>
        </div>

        @if ($post->type === 'image' && $post->media_url)
            <div style="margin:0 -20px 14px;overflow:hidden;">
                <img src="{{ asset('storage/' . $post->media_url) }}" alt="Post media"
                     style="width:100%;display:block;">
            </div>
        @endif

        @if ($post->title)
            <h2 style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:8px;">{{ $post->title }}</h2>
        @endif

        @if ($post->type === 'quote')
            <blockquote style="font-family:var(--font-display);font-style:italic;font-size:1.1rem;color:var(--accent-burgundy);border-left:3px solid var(--accent);padding-left:14px;margin:8px 0;">
                {{ nl2br(e($post->body)) }}
            </blockquote>
        @elseif ($post->type === 'link' && $post->media_url)
            <a href="{{ $post->media_url }}" target="_blank" rel="noopener"
               style="display:block;font-size:0.85rem;color:var(--accent);word-break:break-all;">{{ $post->media_url }}</a>
        @elseif ($post->body)
            <div style="font-size:0.9rem;color:var(--text-secondary);line-height:1.8;margin-bottom:8px;">
                {{ nl2br(e($post->body)) }}
            </div>
        @endif

        @if ($post->relationLoaded('tags') && $post->tags->isNotEmpty())
            <div style="margin-top:8px;display:flex;gap:4px;flex-wrap:wrap;">
                @foreach ($post->tags as $tag)
                    <span style="font-size:0.65rem;color:var(--accent-burgundy);background:rgba(138,48,80,0.06);padding:2px 10px;border:1px solid rgba(138,48,80,0.1);text-transform:uppercase;">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('posts.reblog') }}">
        @csrf
        <input type="hidden" name="parent_post_id" value="{{ $post->id }}">

        <label for="comment">Adaugă un comentariu</label>
        <textarea name="comment" id="comment" rows="4" placeholder="Spune ceva despre această postare...">{{ old('comment') }}</textarea>

        <button type="submit" class="btn btn-primary btn-block">Distribuie</button>
    </form>
</div>
@endsection
