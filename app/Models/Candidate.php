<?php

namespace App\Models;

use App\Casts\URL;
use App\Http\Requests\Api\Candidate\SearchRequest;
use App\Http\Requests\Candidate\StoreRequest;
use App\Http\Requests\Candidate\UpdateRequest;
use App\Traits\CanSortRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class Candidate extends Authenticatable
{
    use HasFactory, CanSortRecords;

    protected $fillable = [
        'name',
        'email',
        'password',
        'curriculum',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'curriculum' => URL::class,
    ];

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
                            ->paginate($request->validated('items', 20));
    }

    public static function create(StoreRequest $request): void
    {
        static::query()->create([
            ...$request->except(['curriculum']),
            'curriculum' => $request->file('curriculum')->store('curriculums'),
        ]);
    }

    public function updateRecord(UpdateRequest $request): void
    {
        $data = $request->except(['curriculum', 'password']);
        if ($password = $request->validated('password')) {
            $data['password'] = Hash::make($password);
        }
        if ($file = $request->file('curriculum')) {
            $data['curriculum'] = $file->store('curriculums');
        }
        $this->update($data);
    }

    protected static function getOrderByFields(): array
    {
        return [
            'name',
            'email',
        ];
    }
}
