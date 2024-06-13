<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectAuthFromGame
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('auth_from_game')) {
            $request->session()->put('auth_from_game', $request->input('auth_from_game'));
        }

        return $next($request);
    }
}
