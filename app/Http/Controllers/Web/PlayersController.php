<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GameRound;
use App\Models\Player;
use App\Models\PlayerConnection;
use App\Models\PlayerHighscore;
use App\Models\PlayerParticipation;
use App\Models\PlayersOnline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PlayersController extends Controller
{
    public function index(Request $request)
    {
        // Cache::forget('player_participations_last_year');
        $participations = Cache::remember(
            'player_participations_last_year',
            Carbon::tomorrow()->startOfDay(),
            function () {
                $data = PlayerParticipation::select(
                    DB::raw('Date(created_at) as date'),
                    DB::raw('count(id) as connections')
                )
                    ->where('created_at', '>=', Carbon::yesterday()->subDays(364))
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();

                $niceData = [];
                foreach ($data as $conn) {
                    $niceData[] = [$conn->date, $conn->connections];
                }

                return $niceData;
            }
        );

        $playerCount = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'players\';'));

        $mostPlayersOnline = PlayersOnline::select(
            DB::raw('sum(online) as total_online'),
            'created_at'
        )
            ->groupBy('created_at')
            ->orderBy('total_online', 'desc')
            ->first();
        $mostPlayersOnline = $mostPlayersOnline->total_online;

        $averagePlayersOnline = PlayersOnline::select(
            DB::raw('avg(online) as average_online')
        )->first();
        $averagePlayersOnline = $averagePlayersOnline->average_online;

        return Inertia::render('Players/Index', [
            'participations' => $participations,
            'totalPlayers' => (int) $playerCount->estimate,
            'mostPlayersOnline' => (int) $mostPlayersOnline,
            'averagePlayersOnline' => (int) $averagePlayersOnline,
        ]);
    }

    public function highscores(Request $request)
    {
        $types = PlayerHighscore::select('type')
            ->distinct('type')
            ->orderBy('type', 'asc')
            ->pluck('type');

        $filters = $request->input('filters', []);
        if (! array_key_exists('type', $filters) && count($types)) {
            $filters['type'] = $types[0];
        }

        $highscores = PlayerHighscore::join('players', 'players.id', '=', 'player_highscores.player_id')
            ->filter($filters)
            ->orderBy(
                $request->input('sort_by', 'value'),
                $request->input('descending', 'true') === 'true' ? 'desc' : 'asc'
            )
            ->select(
                DB::raw('ROW_NUMBER () OVER (ORDER BY "value" desc) as position'),
                'player_highscores.*',
                'players.ckey',
                'players.key'
            )
            ->paginateFilter($request->input('per_page', 10));

        if ($this->wantsInertia($request)) {
            return Inertia::render('Players/Highscores', [
                'highscores' => $highscores,
                'types' => $types,
                'filteredType' => isset($filters['type']) ? $filters['type'] : null,
            ]);
        } else {
            return $highscores;
        }
    }

    public function search(Request $request)
    {
        $players = Player::with('latestConnection')
            ->filter($request->input('filters', []))
            ->orderBy(
                $request->input('sort_by', 'id'),
                $request->input('descending', 'true') === 'true' ? 'desc' : 'asc'
            )
            ->paginateFilter($request->input('per_page', 15));

        if ($this->wantsInertia($request)) {
            return Inertia::render('Players/Search', [
                'players' => $players,
            ]);
        } else {
            return $players;
        }
    }

    public function show(Request $request, int $player)
    {
        $player = Player::with([
            'latestConnection:id,player_id,created_at,round_id',
            'firstConnection:id,player_id,created_at',
            'playtime',
        ])
            ->withCount([
                'connections',
                'participations',
                'deaths',
            ])
            ->where('id', $player)
            ->first();

        // $antagPicks = GameEvent::select(
        //     DB::raw('count(id) as picked'),
        //     'data->traitor_type as traitor_type'
        // )
        //     ->where('player_id', $player->id)
        //     ->where('type', 'antag')
        //     ->groupBy('traitor_type')
        //     ->orderBy('picked', 'desc')
        //     ->limit(3)
        //     ->get();

        $latestRound = null;
        if ($player->latestConnection->round_id) {
            $latestRound = GameRound::where('id', $player->latestConnection->round_id)->first();
        }

        $connections = PlayerConnection::where('player_id', $player->id)
            ->select(
                DB::raw('Date(created_at) as date'),
                DB::raw('count(id) as connections')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(100))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->limit(100)
            ->get();

        return Inertia::render('Players/Show', [
            'player' => $player,
            // 'antagPicks' => $antagPicks,
            'latestRound' => $latestRound,
            'connections' => $connections,
        ]);
    }
}
