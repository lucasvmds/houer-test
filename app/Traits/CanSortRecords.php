<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait CanSortRecords
{
    protected static function setOrderClausule(Builder $builder, string $order): Builder
    {
        list($field, $direction) = explode(':', $order);
        $columns = [
            'id',
            'created_at',
            'updated_at',
            ...static::getOrderByFields(),
        ];
        $direction = match ($direction) {
            'desc' => 'desc',
            'asc' => 'asc',
            default => 'asc',
        };
        $column = Arr::first($columns, fn(string $item): bool => $item === $field, 'id');
        return $builder->orderBy($column, $direction);
    }

    private static function getOrderByFields(): array
    {
        return [];
    }
}