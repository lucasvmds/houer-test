<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Vacancy\SearchRequest;
use App\Http\Requests\Api\Vacancy\StoreRequest;
use App\Http\Requests\Api\Vacancy\UpdateRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyController extends Controller
{
    public function index(SearchRequest $request): JsonResource
    {
        return VacancyResource::collection(Vacancy::getAll($request));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $vacancy = Vacancy::query()->create($request->validated());
        return VacancyResource::make($vacancy)
                        ->response()
                        ->setStatusCode(201);
    }

    public function show(Vacancy $vacancy): JsonResource
    {
        return VacancyResource::make($vacancy);
    }

    public function update(UpdateRequest $request, Vacancy $vacancy): JsonResource
    {
        $vacancy->update($request->validated());
        return VacancyResource::make($vacancy);
    }

    public function destroy(Vacancy $vacancy): JsonResponse
    {
        $vacancy->delete();
        return response()->json(status: 204);
    }
}
