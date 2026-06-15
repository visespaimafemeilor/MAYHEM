<?php

namespace Tests\Feature\Listeners;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PostSavedListenerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.groq.api_key', 'test-key');
    }

    public function test_creates_tags_for_text_post(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => '{"tags": ["călătorie", "aventură", "natură"]}']],
                ],
            ]),
        ]);

        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this
            ->withoutMiddleware()
            ->actingAs($user)
            ->post(route('posts.create'), [
                'type' => 'text',
                'title' => 'O excursie în munți',
                'body' => 'A fost o experiență minunată',
                'status' => 'published',
            ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('tags', ['name' => 'călătorie']);
        $this->assertDatabaseHas('tags', ['name' => 'aventură']);
        $this->assertDatabaseHas('tags', ['name' => 'natură']);
    }

    public function test_does_not_generate_tags_for_image_post(): void
    {
        Http::fake();

        $user = User::create([
            'username' => 'testuser2',
            'email' => 'test2@example.com',
            'password' => 'password',
        ]);

        $this
            ->withoutMiddleware()
            ->actingAs($user)
            ->post(route('posts.create'), [
                'type' => 'image',
                'body' => 'O poză frumoasă',
                'status' => 'published',
            ]);

        Http::assertNothingSent();
    }

    public function test_does_not_generate_tags_for_draft_posts(): void
    {
        Http::fake();

        $user = User::create([
            'username' => 'testuser3',
            'email' => 'test3@example.com',
            'password' => 'password',
        ]);

        $this
            ->withoutMiddleware()
            ->actingAs($user)
            ->post(route('posts.create'), [
                'type' => 'text',
                'title' => 'Draft post',
                'body' => 'Inca in lucru',
                'status' => 'draft',
            ]);

        Http::assertNothingSent();
    }
}
