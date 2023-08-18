<?php

namespace App\Models;

use App\Enums\VacancyType;
use App\Http\Requests\Api\Vacancy\SearchRequest;
use App\Traits\CanSortRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class Vacancy extends Model
{
    use HasFactory, CanSortRecords;

    protected $fillable = [
        'title',
        'description',
        'type',
    ];

    protected $casts = [
        'type' => VacancyType::class,
    ];

    public static function getAll(SearchRequest $request): LengthAwarePaginator
    {
        return static::query()
                            ->when(
                                $request->validated('id'),
                                fn(Builder $builder, string $id): Builder => $builder->where('id', $id),
                            )
                            ->when(
                                $request->validated('title'),
                                fn(Builder $builder, string $title): Builder => $builder->where('title', 'LIKE', "%$title%"),
                            )
                            ->when(
                                $request->validated('description'),
                                fn(Builder $builder, string $description): Builder => $builder->where('description', 'LIKE', "%$description%"),
                            )
                            ->when(
                                $request->validated('type'),
                                fn(Builder $builder, string $type): Builder => $builder->where('type', $type),
                            )
                            ->when(
                                $request->validated('order'),
                                fn(Builder $builder, string $order): Builder => static::setOrderClausule($builder, $order),
                            )
                            ->paginate($request->validated('items', 20));
    }

    protected static function getOrderByFields(): array
    {
        return [
            'title',
            'type',
        ];
    }
}
