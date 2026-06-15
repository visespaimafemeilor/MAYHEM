<article class="post-card post-card--{{ $post->type }}" data-post-id="{{ $post->id }}">

    <div class="post-card-header">
        <a href="{{ route('profile', $post->user->username) }}" class="post-card-author">
            <img src="{{ $post->user->avatar }}"
                 alt="avatar" class="avatar avatar--sm">
            <span>{{ $post->user->username }}</span>
        </a>
        <small class="post-card-date">
            {{ $post->created_at->format('d M Y, H:i') }}
        </small>
    </div>

    @if ($post->type === 'image' && $post->media_url)
        <div class="post-card-media">
            <img src="{{ asset('storage/' . $post->media_url) }}" alt="Post media">
        </div>
    @endif

    @if ($post->title)
        <h2 class="post-card-title">{{ $post->title }}</h2>
    @endif

    @if ($post->type === 'quote')
        <blockquote class="post-card-quote">
            {{ nl2br(e($post->body)) }}
        </blockquote>
    @elseif ($post->type === 'link' && $post->media_url)
        <a href="{{ $post->media_url }}" target="_blank" rel="noopener" class="post-card-link">
            {{ $post->title ?: $post->media_url }}
        </a>
    @elseif ($post->body)
        <div class="post-card-body">
            {{ nl2br(e($post->body)) }}
        </div>
    @endif

    <div class="post-card-actions">
        <button class="btn-like @if ($post->liked) liked @endif"
                data-post-id="{{ $post->id }}">
            &#9829; <span class="like-count">{{ $post->likes_count }}</span>
        </button>

        @auth
            @if (Auth::user()->id === $post->user_id)
                <a href="{{ route('posts.edit', ['id' => $post->id]) }}" class="btn btn-sm">Edit</a>
                <a href="{{ route('posts.delete', ['id' => $post->id]) }}"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Ștergi postarea?')">Șterge</a>
            @endif
        @endauth
    </div>

</article>
