<?php

namespace App\Http\Controllers\Web;

use App\Helpers\ChangelogHelper;
use App\Http\Controllers\Controller;
use App\Models\GameRound;
use App\Models\GameServer;
use App\Models\PlayersOnline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $servers = GameServer::select('id', 'server_id', 'name')
            ->where('active', true)
            ->where('invisible', false)
            ->orderBy('server_id', 'asc')
            ->get();
        $serversToShow = $servers->pluck('server_id');

        // Get the sum of players online for each server, grouped by when we fetched them (usually 5 minute intervals)
        // Then, get the average of that total grouped by day
        $playersOnlineHistory = DB::table(function ($query) use ($serversToShow) {
            $query
                ->select('created_at')
                ->selectRaw('Date(created_at) as date')
                ->selectRaw('sum(online) as online')
                ->whereIn('server_id', $serversToShow)
                ->where('created_at', '>=', Carbon::today()->subDays(7))
                ->where('created_at', '<', Carbon::today())
                ->where('online', '!=', null)
                ->from('players_online')
                ->groupBy('created_at');
        }, 'grouped')
            ->selectRaw('grouped.date as date')
            ->selectRaw('avg(grouped.online) as online')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Just get the sum of players online right now
        $playersOnlineRightNow = PlayersOnline::selectRaw('Date(created_at) as date')
            ->selectRaw('sum(online) as online')
            ->whereIn('server_id', $serversToShow)
            ->where('created_at', '>', Carbon::today())
            ->groupBy('created_at')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        $playersOnline = $playersOnlineHistory->toArray();
        if ($playersOnlineRightNow) {
            $playersOnline[] = $playersOnlineRightNow->toArray();
        }

        $lastRounds = [];
        foreach ($serversToShow as $server) {
            $lastRound = GameRound::with([
                'server:server_id,name',
                'latestStationName:round_id,name',
            ])
                ->select('id', 'server_id', 'created_at', 'ended_at')
                ->where('server_id', $server)
                ->whereNotNull('ended_at')
                ->orderByRaw('created_at DESC NULLS LAST')
                ->first();

            if ($lastRound) {
                $lastRounds[] = $lastRound;
            }
        }

        $changelog = ChangelogHelper::get(7);

        return Inertia::render('Home/Index', [
            'servers' => $servers,
            'playersOnline' => $playersOnline,
            'lastRounds' => $lastRounds,
            'changelog' => $changelog,
        ]);
    }
}
