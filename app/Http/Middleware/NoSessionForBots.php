<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Crawler;
use Illuminate\Http\Request;

class NoSessionForBots
{
    public function handle(Request $request, Closure $next)
    {
        if (Crawler::isCrawler()) {
            Config::set('session.driver', 'array');
        }

        return $next($request);
    }
}
