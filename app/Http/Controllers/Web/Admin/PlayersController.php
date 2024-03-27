<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\CursedCompId;
use App\Models\GameRound;
use App\Models\Player;
use App\Traits\IndexableQuery;
use App\Traits\ManagesBans;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayersController extends Controller
{
    use IndexableQuery, ManagesBans;

    public function index(Request $request)
    {
        $players = $this->indexQuery(Player::withCount(['connections', 'participations']), perPage: 30);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Players/Index', [
                'players' => $players,
            ]);
        } else {
            return $players;
        }
    }

    public function show(Request $request, Player $player)
    {
        $player->load([
            'connections' => function ($q) {
                $q->orderBy('id', 'asc');
            },
            'jobBans' => function ($q) {
                $q->withTrashed()
                    ->orderBy('id', 'desc');
            },
            'jobBans.gameAdmin',
            'jobBans.gameServer',
            'playtime',
            'vpnWhitelist:id,ckey',
            'notes' => function ($q) {
                $q->orderBy('id', 'desc');
            },
            'notes.gameAdmin',
            'notes.gameServer',
        ])
            ->loadCount([
                'participations',
                'participationsRp',
            ]);

        $latestRound = null;
        $latestConnection = $player->connections->last();
        if ($latestConnection?->round_id) {
            $latestRound = GameRound::with(['latestStationName'])
                ->where('id', $latestConnection->round_id)
                ->first();
        }

        $banHistory = $this->banHistory(
            $player->ckey,
            $player->connections->pluck('comp_id')->unique(),
            $player->connections->pluck('ip')->unique(),
        );

        $ips = $player->connections->pluck('ip')->unique()->values();
        $compIds = $player->connections->pluck('comp_id')->unique()->values();

        // Remove any cursed computer IDs (those that are known to belong to shared/common computers)
        $cursedCompIds = CursedCompId::all();
        $compIds = $compIds->diff($cursedCompIds->pluck('comp_id')->values());

        $otherAccounts = Player::with(['latestConnection'])
            ->whereHas('connections', function ($query) use ($ips, $compIds) {
                $query->whereIn('ip', $ips)
                    ->orWhereIn('comp_id', $compIds);
            })
            ->where('id', '!=', $player->id)
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Admin/Players/Show', [
            'player' => $player,
            'latestRound' => $latestRound,
            'banHistory' => $banHistory,
            'otherAccounts' => $otherAccounts,
            'cursedCompIds' => $cursedCompIds,
        ]);
    }

    public function showByCkey(string $ckey)
    {
        $player = Player::where('ckey', $ckey)->firstOrFail();

        return redirect()->route('admin.players.show', $player->id);
    }
}
