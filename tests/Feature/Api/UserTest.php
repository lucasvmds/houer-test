<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_can_fetch_all_users(): void
    {
        User::factory()->create();
        $response = $this
                        ->auth()
                        ->getJson('/api/users');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1);
            });
    }

    public function test_can_fetch_all_users_with_filter(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $response = $this
                        ->auth()
                        ->getJson('/api/users?order=name:asc&name=Test&email=exam&id=1');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1);
            });
    }

    public function test_fetch_all_users_with_filter_return_no_records(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $response = $this
                        ->auth()
                        ->getJson('/api/users?order=name:asc&name=Tests&email=exam');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 0);
            });
    }

    public function test_can_create_user(): void
    {
        $response = $this
                        ->auth()
                        ->postJson('/api/users', [
                            'name' => 'Test User',
                            'email' => 'test@example.com',
                            'password' => 'Ab123456789@',
                            'password_confirmation' => 'Ab123456789@',
                        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas((new User)->getTable(), [
            'id' => $response->json('data.id'),
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_can_fetch_user(): void
    {
        $user = User::factory()->create();
        $response = $this
                        ->auth()
                        ->get("/api/users/$user->id");
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_can_update_user(): void
    {
        $user = User::factory()->create();
        $user->email = 'auto.test@example.com';
        $user->name = 'Test User';
        $response = $this
                        ->auth()
                        ->patchJson("/api/users/$user->id", [
                            'name' => 'Test User',
                            'email' => 'auto.test@example.com',
                            'password' => 'Ab123456789@',
                            'password_confirmation' => 'Ab123456789@',
                        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas($user->getTable(), [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }

    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();
        $response = $this
                        ->auth()
                        ->delete("/api/users/$user->id");
        $response->assertStatus(204);
        $this->assertModelMissing($user);
    }
}
