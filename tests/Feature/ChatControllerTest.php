<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\ChatController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_uses_gemini_generate_content_payload(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo! Saya siap membantu.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        config(['services.gemini.key' => 'test-key']);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $controller = new ChatController();
        $request = new Request(['message' => 'Halo']);
        $request->setUserResolver(fn () => $user);

        $response = $controller->sendMessage($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Halo! Saya siap membantu.', $response->getData(true)['reply']);

        Http::assertSent(function ($request) {
            $this->assertStringContainsString('generateContent', $request->url());
            $this->assertStringContainsString('key=test-key', $request->url());

            $body = $request->data();
            $this->assertArrayHasKey('contents', $body);
            $this->assertNotEmpty($body['contents']);

            return true;
        });
    }

    public function test_it_falls_back_to_a_supported_gemini_model_when_latest_is_not_available(): void
    {
        Http::fakeSequence()
            ->push([
                'error' => ['message' => 'not found'],
            ], 404)
            ->push([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo! Saya siap membantu.'],
                            ],
                        ],
                    ],
                ],
            ], 200);

        config(['services.gemini.key' => 'test-key']);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $controller = new ChatController();
        $request = new Request(['message' => 'Halo']);
        $request->setUserResolver(fn () => $user);

        $response = $controller->sendMessage($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Halo! Saya siap membantu.', $response->getData(true)['reply']);
        $this->assertSame(2, Http::recorded()->count());
    }

    public function test_it_returns_a_fallback_reply_when_gemini_is_rate_limited(): void
    {
        Http::fakeSequence()
            ->push([
                'error' => ['message' => 'quota exceeded'],
            ], 429);

        config(['services.gemini.key' => 'test-key']);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test3@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $controller = new ChatController();
        $request = new Request(['message' => 'Saya merasa stres']);
        $request->setUserResolver(fn () => $user);

        $response = $controller->sendMessage($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('tidak bisa mengakses layanan AI saat ini', $response->getData(true)['reply']);
    }
}
