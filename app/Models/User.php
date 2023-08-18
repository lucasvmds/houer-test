<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Requests\Api\User\SearchRequest;
use App\Traits\CanSortRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CanSortRecords;

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
        return $token->tokenable()->first();
    }

    public static function getAll(SearchRequest $request): LengthAwarePaginator
    {
        return static::query()
                            ->when(
                                $request->validated('id'),
                                fn(Builder $builder, string $id): Builder => $builder->where('id', $id),
                            )
                            ->when(
                                $request->validated('name'),
                                fn(Builder $builder, string $name): Builder => $builder->where('name', 'LIKE', "%$name%"),
                            )
                            ->when(
                                $request->validated('email'),
                                fn(Builder $builder, string $email): Builder => $builder->where('email', 'LIKE', "%$email%"),
                            )
                            ->when(
                                $request->validated('order'),
                                fn(Builder $builder, string $order): Builder => static::setOrderClausule($builder, $order),
                            )
                            ->where('id', '!=', static::current($request)->id)
                            ->paginate($request->validated('items', 20));
    }

    protected static function getOrderByFields(): array
    {
        return [
            'name',
            'email',
        ];
    }

    public static function create(array $data): static
    {
        $data['password'] = Hash::make($data['password']);
        return static::query()->create($data);
    }

    public function updateRecord(array $data): static
    {
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $this->update($data);
        return $this;
    }
}
