<?php

namespace Database\Seeders\Production;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('Ab123456789@'),
        ]);
    }
}
