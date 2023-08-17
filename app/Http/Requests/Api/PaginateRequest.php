<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaginateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'page' => 'nullable|numeric',
            'items' => 'nullable|numeric',
            'order' => 'nullable|string',
        ];
    }
}
