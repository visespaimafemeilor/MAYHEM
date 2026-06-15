<?php

namespace Tests\Feature\Services\AI;

use App\Services\AI\ContentCuratorAgent;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContentCuratorAgentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.groq.api_key', 'test-key');
    }

    public function test_suggests_tags_on_successful_response(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => '{"tags": ["călătorie", "aventură", "natură"]}']],
                ],
            ]),
        ]);

        $agent = new ContentCuratorAgent('test-key');

        $tags = $agent->suggestTags('O excursie în munți');

        $this->assertEquals(['călătorie', 'aventură', 'natură'], $tags);
    }

    public function test_returns_empty_array_when_api_fails(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([], 500),
        ]);

        $agent = new ContentCuratorAgent('test-key');

        $tags = $agent->suggestTags('ceva conținut');

        $this->assertSame([], $tags);
    }

    public function test_returns_empty_array_when_missing_api_key(): void
    {
        config()->set('services.groq.api_key', null);

        Http::fake();

        $agent = new ContentCuratorAgent(null);

        $tags = $agent->suggestTags('ceva conținut');

        $this->assertSame([], $tags);
        Http::assertNothingSent();
    }

    public function test_returns_empty_array_for_empty_content(): void
    {
        Http::fake();

        $agent = new ContentCuratorAgent('test-key');

        $this->assertSame([], $agent->suggestTags(''));
        $this->assertSame([], $agent->suggestTags('   '));
        Http::assertNothingSent();
    }

    public function test_returns_empty_array_when_json_missing_tags_key(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => '{"not_tags": ["ceva"]}']],
                ],
            ]),
        ]);

        $agent = new ContentCuratorAgent('test-key');

        $this->assertSame([], $agent->suggestTags('content'));
    }
}
