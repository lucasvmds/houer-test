<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function login(array $data): static | false
    {
        $user = static::query()
                            ->where('email', $data['email'])
                            ->first();
        if (!$user) return false;
        return Hash::check($data['password'], $user->password) ? $user : false;
    }

    public function generateToken(bool $remember): string
    {
        $this->cleanTokens();
        $expiration = $remember ? null : now()->addHour();
        return $this
                    ->createToken(name: 'access_token', expiresAt: $expiration)
                    ->plainTextToken;
    }

    public static function logout(Request $request): void
    {
        static::current($request)?->cleanTokens();
    }

    public function cleanTokens(): void
    {
        $this
            ->tokens()
            ->delete();
    }

    private static function current(Request $request): static | null
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if (!$token) return null;
        return $token->tokenable()->first() ?? null;
    }
}
