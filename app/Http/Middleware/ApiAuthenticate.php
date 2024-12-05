<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $bearer = $request->bearerToken();
        if ($bearer) {
            $pat = null;
            if (str_contains($bearer, '|')) {
                $tokenParts = explode('|', $bearer, 2);
                if (count($tokenParts) === 2) {
                    $id = $tokenParts[0];
                    $token = $tokenParts[1];
                    $pat = PersonalAccessToken::find($id);
                    if (hash('sha256', $token) !== $pat->token) {
                        $pat = null;
                    }
                }
            } else {
                $pat = PersonalAccessToken::where('token', hash('sha256', $bearer))
                    ->first();
            }

            if ($pat) {
                if ($user = User::find($pat->tokenable_id)) {
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });

                    return $next($request);
                }
            }
        }

        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
