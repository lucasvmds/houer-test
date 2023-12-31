<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function auth(): static
    {
        /** @var User */
        $user = User::factory()->create();
        $token = $user->generateToken(false);
        return $this->withToken($token);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }
}
