<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    $pat = DB::table('personal_access_tokens')->find($id);
                    if (hash('sha256', $token) !== $pat->token) $pat = null;
                }
            } else {
                $pat = DB::table('personal_access_tokens')
                    ->where('token', hash('sha256', $bearer))
                    ->first();
            }

            if ($pat) {
                if ($user = \App\Models\User::find($pat->tokenable_id)) {
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
