<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_can_login(): void
    {
        $user = User::factory()->create([
            'password' => '123456',
        ]);
        $response = $this->postJson('/api/auth', [
            'email' => $user->email,
            'password' => '123456',
            'remember' => false,
        ]);
        $response
            ->assertStatus(201)
            ->assertJson(function(AssertableJson $json): void {
                $json->whereType('data.access_token', 'string');
            });
    }

    public function test_can_logout(): void
    {
        /** @var User */
        $user = User::factory()->create();
        $token = $user->generateToken(false);
        $token_model = $user->tokens()->first();
        $response = $this
                        ->withToken($token)
                        ->deleteJson('/api/auth');
        $response->assertStatus(204);
        $this->assertModelMissing($token_model);
    }
}
