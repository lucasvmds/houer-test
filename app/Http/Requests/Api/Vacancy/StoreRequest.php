<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Vacancy;

use App\Enums\VacancyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => [
                'required',
                Rule::enum(VacancyType::class),
            ],
        ];
    }
}
