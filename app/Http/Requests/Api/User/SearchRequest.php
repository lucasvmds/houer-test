<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\PaginateRequest;

class SearchRequest extends PaginateRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'id' => 'nullable|numeric',
            'name' => 'nullable|string',
            'email' => 'nullable|string',
        ];
    }
}
