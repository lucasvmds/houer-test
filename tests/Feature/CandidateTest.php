<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidateTest extends TestCase
{
    public function test_can_create_candidate(): void
    {
        $response = $this
                        ->post('/cadastrar', [
                            'name' => 'Test Candidate',
                            'email' => 'test@example.com',
                            'password' => 'Ab123456789@',
                            'password_confirmation' => 'Ab123456789@',
                            'curriculum' => UploadedFile::fake()->image('file.png'),
                        ]);
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirectToRoute('pages.vacancies');
        $this->assertDatabaseHas((new Candidate)->getTable(), [
            'name' => 'Test Candidate',
            'email' => 'test@example.com',
        ]);
    }

    public function test_can_update_candidate(): void
    {   
        $candidate = Candidate::factory()->create();
        $candidate->email = 'auto.test@example.com';
        $candidate->name = 'Test Candidate';
        $response = $this
                        ->actingAs($candidate)
                        ->post("/perfil", [
                            'name' => 'Test Candidate',
                            'email' => 'auto.test@example.com',
                            'password' => 'Ab123456789@',
                            'password_confirmation' => 'Ab123456789@',
                            '_method' => 'PATCH',
                        ]);
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirectToRoute('pages.profile');
        $this->assertDatabaseHas($candidate->getTable(), [
            'id' => $candidate->id,
            'email' => $candidate->email,
            'name' => $candidate->name,
        ]);
    }

    public function test_candidate_can_delete_your_account(): void
    {
        $candidate = Candidate::factory()->create();
        $response = $this
                        ->actingAs($candidate)
                        ->post('/perfil', [
                            '_method' => 'DELETE',
                        ]);
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirectToRoute('login');
        $this->assertModelMissing($candidate);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }
}
