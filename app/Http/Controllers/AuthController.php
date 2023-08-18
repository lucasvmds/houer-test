<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(): Response | RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('pages.vacancies');
        } else {
            return response()->view('pages.login');
        }
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $remember = Arr::pull($data, 'remember', false);
        if (Auth::attempt($data, $remember)) {
            return redirect()->route('pages.profile');
        } else {
            return redirect()
                        ->route('login')
                        ->withErrors(['login' => 'As credenciais informadas são incorretas'])
                        ->withInput();
        }
    }
}
