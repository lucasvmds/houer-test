<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_token = $request->bearerToken();
        $token = PersonalAccessToken::findToken($access_token);
        abort_unless($token, 401);
        if ($token->expires_at) {
            if (now()->greaterThanOrEqualTo($token->expires_at)) {
                $token->delete();
                abort(401);
            }
            $token->update(['expires_at' => now()->addHour()]);
        }
        return $next($request);
    }
}
