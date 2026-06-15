<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class ContentCuratorAgent
{
    protected ?string $apiKey;
    protected string $model;

    public function __construct(
        ?string $apiKey = null,
        ?string $model = null,
    ) {
        $this->apiKey = $apiKey ?? config('services.groq.api_key');
        $this->model = $model ?? config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    public function suggestTags(string $content): array
    {
        if (empty($this->apiKey) || empty(trim($content))) {
            return [];
        }

        $systemPrompt = "Ești un specialist în analiză de conținut și generare de tag-uri relevante.";

        $userPrompt = "Analizează următorul conținut și generează între 3 și 5 tag-uri relevante care să descrie subiectul. Tag-urile trebuie să fie concise (1-3 cuvinte fiecare). Răspunde strict în format JSON cu cheia \"tags\" care conține un array de string-uri.\n\nExemplu: {\"tags\": [\"călătorie\", \"aventură\", \"natură\"]}\n\nConținut: {$content}";

        $response = Http::withToken($this->apiKey)
            ->timeout(30)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 0.3,
                'max_tokens' => 300,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            logger()->error('Groq API call failed in ContentCuratorAgent', [
                'status' => $response->status(),
                'body' => $response->json('error.message') ?: $response->body(),
            ]);
            return [];
        }

        $rawContent = $response->json('choices.0.message.content');
        $parsed = $this->parseJson($rawContent);

        if ($parsed === null || !isset($parsed['tags']) || !is_array($parsed['tags'])) {
            return [];
        }

        return array_values(array_filter(array_map('trim', $parsed['tags'])));
    }

    private function parseJson(string $text): ?array
    {
        $text = preg_replace('/^```(?:json)?\s*\n?/', '', $text);
        $text = preg_replace('/\n?```\s*$/', '', $text);
        $text = trim($text);

        $parsed = json_decode($text, true);

        return json_last_error() === JSON_ERROR_NONE ? $parsed : null;
    }
}
