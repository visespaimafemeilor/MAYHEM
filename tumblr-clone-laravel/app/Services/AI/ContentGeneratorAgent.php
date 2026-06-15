<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class ContentGeneratorAgent
{
    protected ?string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    public function generate(string $idea, string $type = 'text'): array
    {
        if (empty($this->apiKey)) {
            return [
                'title' => 'Cheie API lipsă',
                'body' => 'Adaugă GROQ_API_KEY în fișierul .env și repornește containerul: docker compose restart app',
            ];
        }

        $systemPrompt = "Ești un microblogger obscur și profund pe o platformă stil Tumblr. Generează conținut scurt, misterios și de impact.";

        $userPrompt = match ($type) {
            'quote' => "Pe baza ideii „{$idea}”, generează un citat original profund. Răspunde strict în format JSON cu cheile \"title\" (autorul aparent) și \"body\" (citatul).",
            default => "Pe baza ideii „{$idea}”, generează o postare scurtă de microblog. Răspunde strict în format JSON cu cheile \"title\" și \"body\".",
        };

        $response = Http::withToken($this->apiKey)
            ->timeout(30)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 0.9,
                'max_tokens' => 500,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            $errorBody = $response->json('error.message') ?: $response->body();
            logger()->error('Groq API call failed', [
                'status' => $response->status(),
                'body' => $errorBody,
            ]);
            return [
                'title' => 'Eroare Groq',
                'body' => $errorBody,
            ];
        }

        $content = $response->json('choices.0.message.content');
        $parsed = $this->parseJson($content);

        if ($parsed === null) {
            return [
                'title' => 'Eroare de procesare',
                'body' => $content,
            ];
        }

        return [
            'title' => $parsed['title'] ?? '',
            'body' => $parsed['body'] ?? $content,
        ];
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