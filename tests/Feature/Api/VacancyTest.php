<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Vacancy;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VacancyTest extends TestCase
{
    public function test_can_fetch_all_vacancies(): void
    {
        Vacancy::factory()->create();
        $response = $this
                        ->auth()
                        ->getJson('/api/vacancies');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1);
            });
    }

    public function test_can_fetch_all_vacancies_with_filter(): void
    {
        Vacancy::factory()->create([
            'title' => 'Test Vacancy',
            'description' => 'Test Description',
        ]);
        $response = $this
                        ->auth()
                        ->getJson('/api/vacancies?order=title:asc&title=Test&description=Descri&id=1');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1);
            });
    }

    public function test_fetch_all_vacancies_with_filter_return_no_records(): void
    {
        Vacancy::factory()->create([
            'title' => 'Test Vacancy',
            'description' => 'Test Description',
        ]);
        $response = $this
                        ->auth()
                        ->getJson('/api/vacancies?order=title:asc&title=Tests&description=exam');
        $response
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json): void {
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 0);
            });
    }

    public function test_can_create_vacancy(): void
    {
        $response = $this
                        ->auth()
                        ->postJson('/api/vacancies', [
                            'title' => 'Test Vacancy',
                            'description' => 'Test Description',
                        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas((new Vacancy)->getTable(), [
            'id' => $response->json('data.id'),
            'title' => 'Test Vacancy',
            'description' => 'Test Description',
        ]);
    }

    public function test_can_fetch_vacancy(): void
    {
        $vacancy = Vacancy::factory()->create();
        $response = $this
                        ->auth()
                        ->get("/api/vacancies/$vacancy->id");
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $vacancy->id,
                'title' => $vacancy->title,
                'description' => $vacancy->description,
            ]);
    }

    public function test_can_update_vacancy(): void
    {
        $vacancy = Vacancy::factory()->create();
        $vacancy->title = 'New Title';
        $vacancy->description = 'New Description';
        $response = $this
                        ->auth()
                        ->patchJson("/api/vacancies/$vacancy->id", [
                            'title' => 'New Title',
                            'description' => 'New Description',
                        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas($vacancy->getTable(), [
            'id' => $vacancy->id,
            'title' => $vacancy->title,
            'description' => $vacancy->description,
        ]);
    }

    public function test_can_delete_vacancy(): void
    {
        $vacancy = Vacancy::factory()->create();
        $response = $this
                        ->auth()
                        ->delete("/api/vacancies/$vacancy->id");
        $response->assertStatus(204);
        $this->assertModelMissing($vacancy);
    }
}
