<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Candidate\SearchRequest;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateController extends Controller
{
    public function index(SearchRequest $request): JsonResource
    {
        return CandidateResource::collection(Candidate::getAll($request));
    }

    public function show(Candidate $candidate): JsonResource
    {
        return CandidateResource::make($candidate);
    }

    public function destroy(Candidate $candidate): JsonResponse
    {
        $candidate->delete();
        return response()->json(status: 204);
    }
}
