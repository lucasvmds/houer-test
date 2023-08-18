<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $local_seeders = [
            \Database\Seeders\Develop\CandidateSeeder::class,
            \Database\Seeders\Develop\UserSeeder::class,
            \Database\Seeders\Develop\VacancySeeder::class,
        ];

        $productions_seeders = [
            \Database\Seeders\Production\UserSeeder::class,
        ];

        $this->call([
            ...$productions_seeders,
            ...(App::environment('local') ? $local_seeders : []),
        ]);
    }
}
