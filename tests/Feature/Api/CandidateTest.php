<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Candidate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CandidateTest extends TestCase
{
    public function test_can_fetch_all_candidates(): void
    {
        Candidate::factory()->create();
        $response = $this
                        ->auth()
                        ->getJson('/api/candidates');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1);
            });
    }

    public function test_can_fetch_all_candidates_with_filter(): void
    {
        Candidate::factory()->create([
            'name' => 'Test Candidate',
            'email' => 'test@example.com',
        ]);
        $response = $this
                        ->auth()
                        ->getJson('/api/candidates?order=name:asc&name=Test&email=exam&id=1');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1);
            });
    }

    public function test_fetch_all_candidates_with_filter_return_no_records(): void
    {
        Candidate::factory()->create([
            'name' => 'Test Candidate',
            'email' => 'test@example.com',
        ]);
        $response = $this
                        ->auth()
                        ->getJson('/api/candidates?order=name:asc&name=Tests&email=exam');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 0);
            });
    }

    public function test_can_fetch_candidate(): void
    {
        $candidate = Candidate::factory()->create();
        $response = $this
                        ->auth()
                        ->get("/api/candidates/$candidate->id");
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $candidate->id,
                'name' => $candidate->name,
                'email' => $candidate->email,
            ]);
    }

    public function test_can_delete_candidate(): void
    {
        $candidate = Candidate::factory()->create();
        $response = $this
                        ->auth()
                        ->delete("/api/candidates/$candidate->id");
        $response->assertStatus(204);
        $this->assertModelMissing($candidate);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }
}
