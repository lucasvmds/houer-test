<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::login($request->validated());
        if (!$user) return response()->json(status: 401);
        $remember = (bool) $request->validated('remember', false);
        return response()->json(
            [
                'data' => [
                    'access_token' => $user->generateToken($remember),
                ],
            ],
            201
        );
    }

    public function logout(Request $request): JsonResponse
    {
        User::logout($request);
        return response()->json(status: 204);
    }
}
