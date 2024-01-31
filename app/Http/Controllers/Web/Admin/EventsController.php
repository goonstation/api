<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $redis = null;
        $connectionError = null;

        try {
            $redis = Redis::connection('events');
        } catch (\Exception $e) {
            $connectionError = $e->getMessage();
        }

        $eventHistory = $redis ? $redis->command('lrange', ['event_history', 0, -1]) : [];

        $response = [
            'events' => $eventHistory,
            'connectionError' => $connectionError
        ];

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Events/Index', $response);
        } else {
            return $response;
        }
    }
}
