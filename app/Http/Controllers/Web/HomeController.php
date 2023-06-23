<?php

namespace App\Http\Controllers\Web;

use App\Helpers\ChangelogHelper;
use App\Http\Controllers\Controller;
use App\Models\GameRound;
use App\Models\PlayersOnline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $serversToShow = [
            'main1',
            'main3',
            'main4',
            'main5',
        ];

        $playersOnline = PlayersOnline::whereIn('server_id', $serversToShow)
            ->where('created_at', '>=', Carbon::today()->subDays(7))
            ->groupBy('date')
            ->get([
                DB::raw('Date(created_at) as "date"'),
                DB::raw('avg(online) as "online"'),
            ]);

        $lastRounds = [];
        foreach ($serversToShow as $server) {
            $lastRounds[] = GameRound::where('server_id', $server)
                ->whereNotNull('ended_at')
                ->orderByRaw('created_at DESC NULLS LAST')
                ->first();
        }

        $changelog = ChangelogHelper::get(7);

        return Inertia::render('Home/Index', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'serversToShow' => $serversToShow,
            'playersOnline' => $playersOnline,
            'lastRounds' => $lastRounds,
            'changelog' => $changelog,
        ]);
    }
}
