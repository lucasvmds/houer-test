<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Candidate\StoreRequest;
use App\Http\Requests\Candidate\UpdateRequest;
use App\Models\Candidate;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function create()
    {
        //
    }
    
    public function store(StoreRequest $request): RedirectResponse
    {
        Candidate::create($request);
        if (Auth::attempt($request->only(['password', 'email']))) {
            return redirect()->route('pages.vacancies');
        } else {
            return redirect()->route('login');
        }
    }

    public function edit(Candidate $candidate)
    {
        //
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        /** @var Candidate */
        $candidate = $request->user();
        $candidate->updateRecord($request);
        return redirect()->route('pages.profile');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->user()->delete();
        return redirect()->route('login');
    }
}
