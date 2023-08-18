<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    private static string $file_path = '';
    // private static string $file_content = '';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (!static::$file_path) {
            static::$file_path = fake()->image();
        }
        // if (!static::$file_content) {
        //     static::$file_content = file_get_contents(
        //         fake()->image(),
        //     );
        // }
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'curriculum' => Storage::putFile('curriculums', static::$file_path),
            // 'curriculum' => Storage::putFile('curriculums', static::$file_content),
        ];
    }
}
