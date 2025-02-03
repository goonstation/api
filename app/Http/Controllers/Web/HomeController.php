<?php

namespace App\Http\Controllers\Web;

use App\Facades\GameBridge;
use App\Helpers\ChangelogHelper;
use App\Http\Controllers\Controller;
use App\Models\GameRound;
use App\Models\GameServer;
use App\Models\PlayersOnline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\SchemaOrg\GamePlayMode;
use Spatie\SchemaOrg\Schema;

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

        $this->setMeta(
            description: 'Dive into the world of Space Station 13\'s Goonstation branch with Goonhub. Get access to comprehensive statistics and stay up-to-date with the latest developments.',
            image: ['type' => 'home', 'key' => 1]
        );
        $this->setHomeSchema();

        return Inertia::render('Home/Index', [
            'servers' => $servers,
            'playersOnline' => $playersOnline,
            'lastRounds' => $lastRounds,
            'changelog' => $changelog,
        ]);
    }

    private function setHomeSchema()
    {
        $author = Schema::organization()
            ->name('Goonstation')
            ->url('https://github.com/goonstation/goonstation');

        $playMode = Schema::gamePlayMode()
            ->name(GamePlayMode::MultiPlayer);

        $numberOfPlayers = Schema::quantitativeValue()
            ->value('Number')
            ->minValue(0)
            ->maxValue(200);

        $language = Schema::language()
            ->name('English')
            ->alternateName('en');

        $game = Schema::videoGame()
            ->name('Goonstation')
            ->description('')
            ->author($author)
            ->genre([''])
            ->operatingSystem('Windows')
            ->gamePlatform(['PC game'])
            ->playMode($playMode)
            ->numberOfPlayers($numberOfPlayers)
            ->downloadUrl('https://www.byond.com/download/')
            ->inLanguage($language);

        $this->setSchema($game);
    }

    public static function getOpenGraphData()
    {
        $servers = GameServer::select(['server_id', 'name', 'address', 'port'])
            ->where('active', true)
            ->where('invisible', false)
            ->orderBy('server_id', 'asc')
            ->get();

        $data = ['servers' => $servers->toArray()];
        foreach ($servers as $key => $server) {
            $res = GameBridge::create()
                ->target($server)
                ->message('status')
                ->send();

            if ($res->error) {
                $data['servers'][$key]['error'] = true;

                continue;
            }

            parse_str($res->message, $status);
            $data['servers'][$key]['status'] = $status;
        }

        return $data;
    }
}
