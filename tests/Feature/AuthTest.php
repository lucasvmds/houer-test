<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_can_visit_login_page(): void
    {
        $response = $this->get('/');
        $response
            ->assertStatus(200)
            ->assertSeeInOrder([
                'E-Mail',
                'Senha',
                'Manter conectado',
                'Entrar',
                'Fazer cadastro',
            ]);
    }

    public function test_candidate_can_successfully_login(): void
    {
        $candidate = Candidate::factory()->create([
            'password' => '123456789',
        ]);
        $response = $this->post('/', [
            'email' => $candidate->email,
            'password' => '123456789',
        ]);
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirectToRoute('pages.profile');
    }
}
