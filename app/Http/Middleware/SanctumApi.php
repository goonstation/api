<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SanctumApi
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
        if (! $bearer) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        [$id, $token] = explode('|', $bearer, 2);
        $instance = DB::table('personal_access_tokens')->find($id);

        if (hash('sha256', $token) === $instance->token) {
            if ($user = \App\Models\User::find($instance->tokenable_id)) {
                Auth::setUser($user);
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Unauthenticated.',
        ], 401);
    }
}
