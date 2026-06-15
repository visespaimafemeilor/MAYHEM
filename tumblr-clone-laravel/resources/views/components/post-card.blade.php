<article class="post-card post-card--{{ $post->type }}" data-post-id="{{ $post->id }}">

    @if ($post->isReblog() && $post->relationLoaded('parentPost') && $post->parentPost)
        <div class="post-card-reblog-header">
            &#128257; a distribuit de la
            <a href="{{ route('profile', $post->parentPost->user->username) }}">
                @<span>{{ $post->parentPost->user->username }}</span>
            </a>
        </div>
    @endif

    <div class="post-card-content">

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
            <a href="{{ route('posts.show', $post->id) }}" class="post-card-media-link">
                <div class="post-card-media">
                    <img src="{{ asset('storage/' . $post->media_url) }}" alt="Post media">
                </div>
            </a>
        @endif

        @if ($post->title)
            <a href="{{ route('posts.show', $post->id) }}" class="post-card-title-link">
                <h2 class="post-card-title">{{ $post->title }}</h2>
            </a>
        @endif

        @if ($post->type === 'quote')
            <a href="{{ route('posts.show', $post->id) }}" class="post-card-quote-link">
                <blockquote class="post-card-quote">
                    {{ nl2br(e($post->body)) }}
                </blockquote>
            </a>
        @elseif ($post->type === 'link' && $post->media_url)
            <a href="{{ $post->media_url }}" target="_blank" rel="noopener" class="post-card-link">
                {{ $post->title ?: $post->media_url }}
            </a>
        @elseif ($post->body)
            <a href="{{ route('posts.show', $post->id) }}" class="post-card-body-link">
                <div class="post-card-body">
                    {{ nl2br(e($post->body)) }}
                </div>
            </a>
        @endif

        @if ($post->relationLoaded('tags') && $post->tags->isNotEmpty())
            <div class="post-card-tags">
                @foreach ($post->tags as $tag)
                    <a href="{{ route('dashboard', ['tag' => $tag->slug]) }}" class="tag-link">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="post-card-actions">
        <button class="btn-like @if ($post->liked) liked @endif"
                data-post-id="{{ $post->id }}">
            &#9829; <span class="like-count">{{ $post->likes_count }}</span>
        </button>

        @auth
            <button class="btn btn-sm btn-reblog"
                    onclick="openReblogModal('{{ $post->id }}', '{{ $post->user->username }}', {{ json_encode($post->body) }})">
                &#128257; Reblog
            </button>

            @if (Auth::user()->id === $post->user_id)
                <a href="{{ route('posts.edit', ['id' => $post->id]) }}" class="btn btn-sm">Edit</a>
                <a href="{{ route('posts.delete', ['id' => $post->id]) }}"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Ștergi postarea?')">Șterge</a>
            @endif
        @endauth
    </div>

</article>
