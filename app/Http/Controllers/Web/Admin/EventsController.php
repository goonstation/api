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
        $redis = Redis::connection('events');
        $eventHistory = $redis->command('lrange', ['event_history', 0, -1]);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Events/Index', [
                'events' => $eventHistory,
            ]);
        } else {
            return $eventHistory;
        }
    }
}
