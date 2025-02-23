<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NoMeta
{
    public function handle(Request $request, Closure $next)
    {
        Session::now('no_meta', true);

        return $next($request);
    }
}
