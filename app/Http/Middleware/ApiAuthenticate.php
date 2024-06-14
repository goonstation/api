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
            $tokenParts = explode('|', $bearer, 2);
            if (count($tokenParts) === 2) {
                $id = $tokenParts[0];
                $token = $tokenParts[1];
                $instance = DB::table('personal_access_tokens')->find($id);

                if (hash('sha256', $token) === $instance->token) {
                    if ($user = \App\Models\User::find($instance->tokenable_id)) {
                        $request->setUserResolver(function () use ($user) {
                            return $user;
                        });

                        return $next($request);
                    }
                }
            }
        }

        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
