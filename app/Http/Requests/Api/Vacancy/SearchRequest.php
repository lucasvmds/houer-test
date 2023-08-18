<?php

namespace App\Http\Requests\Api\Vacancy;

use App\Http\Requests\Api\PaginateRequest;

class SearchRequest extends PaginateRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'id' => 'nullable|numeric',
            'title' => 'nullable|string',
            'type' => 'nullable|string',
        ];
    }
}
