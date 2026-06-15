<?php

namespace App\Listeners;

use App\Events\PostSaved;
use App\Models\Tag;
use App\Services\AI\ContentCuratorAgent;
use Illuminate\Support\Str;

class PostSavedListener
{
    public function __construct(
        private ContentCuratorAgent $curatorAgent,
    ) {}

    public function handle(PostSaved $event): void
    {
        $post = $event->post;

        if (!in_array($post->type, ['text', 'quote'], true)) {
            return;
        }

        if ($post->status !== 'published') {
            return;
        }

        $content = '';
        if ($post->title) {
            $content .= $post->title . ' ';
        }
        if ($post->body) {
            $content .= $post->body;
        }

        $content = trim($content);
        if (empty($content)) {
            return;
        }

        $suggestedTags = $this->curatorAgent->suggestTags($content);

        if (empty($suggestedTags)) {
            return;
        }

        $newTagIds = collect($suggestedTags)->map(function (string $name): int {
            return Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            )->id;
        })->toArray();

        $existingTagIds = $post->tags()->pluck('tags.id')->toArray();

        $post->tags()->sync(array_unique(array_merge($existingTagIds, $newTagIds)));
    }
}
