<?php

namespace App\Http\Middleware;

use Closure;
use Crawler;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;

class NoSessionForBots
{
    public function __construct(
        private readonly SessionManager $sessionManager,
    ) {}

    public function handle(Request $request, Closure $next)
    {
        if (Crawler::isCrawler($request->userAgent())) {
            $this->sessionManager->getContainer()->make('config')->set('session.driver', 'array');
        }

        return $next($request);
    }
}
