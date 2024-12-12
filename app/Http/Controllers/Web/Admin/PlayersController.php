<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use App\Models\BanDetail;
use App\Models\CursedCompId;
use App\Models\GameRound;
use App\Models\Player;
use App\Models\PlayerConnection;
use App\Traits\IndexableQuery;
use App\Traits\ManagesBans;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayersController extends Controller
{
    use IndexableQuery, ManagesBans;

    public function index(Request $request)
    {
        $model = Player::withCount(['connections', 'participations']);
        if ($request->has('with_latest_connection')) {
            $model = $model->with('latestConnection');
        }
        $players = $this->indexQuery($model, perPage: 30);

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
            'medals.medal',
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

        $ckey = $player->ckey;
        $ips = $player->connections->pluck('ip')->unique()->values();
        $compIds = $player->connections->pluck('comp_id')->unique()->values();

        $banHistory = $banHistory->map(function (Ban $ban) use ($ckey, $ips, $compIds) {
            $ban->setAttribute(
                'player_has_active_details',
                $this->banPlayerHasActiveDetails($ban, $ckey, $ips->toArray(), $compIds->toArray())
            );

            return $ban;
        });

        // Remove any cursed computer IDs (those that are known to belong to shared/common computers)
        $cursedCompIds = CursedCompId::all();
        $compIds = $compIds->diff($cursedCompIds->pluck('comp_id')->values());

        // Get other connection details associated with this player
        $otherIps = PlayerConnection::select(['player_id', 'ip'])
            ->whereIn('ip', $ips)
            ->where('player_id', '!=', $player->id)
            ->distinct()
            ->get();
        $otherCompIds = PlayerConnection::select(['player_id', 'comp_id'])
            ->whereIn('comp_id', $compIds)
            ->where('player_id', '!=', $player->id)
            ->distinct()
            ->get();

        // Get the players for the other connection details
        $otherIpsPlayerIds = $otherIps->pluck('player_id');
        $otherCompIdsPlayerIds = $otherCompIds->pluck('player_id');
        $otherAccounts = Player::with(['latestConnection'])
            ->whereIn(
                'id',
                $otherIpsPlayerIds->merge($otherCompIdsPlayerIds)->unique()
            )
            ->orderBy('id', 'desc')
            ->get();

        // Determine what connection detail the other player has in common with the viewed player
        $otherAccounts->transform(function (Player $account) use ($otherIpsPlayerIds, $otherCompIdsPlayerIds) {
            $account->setAttribute('_matchedOnIp', $otherIpsPlayerIds->containsStrict($account->id));
            $account->setAttribute('_matchedOnCompId', $otherCompIdsPlayerIds->containsStrict($account->id));

            return $account;
        });

        return Inertia::render('Admin/Players/Show', [
            'player' => $player,
            'latestRound' => $latestRound,
            'banHistory' => $banHistory,
            'otherAccounts' => $otherAccounts,
            'cursedCompIds' => $cursedCompIds,
            'uniqueIps' => $ips,
            'uniqueCompIds' => $compIds,
        ]);
    }

    public function showByCkey(string $ckey)
    {
        $player = Player::where('ckey', ckey($ckey))->firstOrFail();

        return redirect()->route('admin.players.show', $player->id);
    }

    private function banPlayerHasActiveDetails(Ban $ban, $ckey, $ips, $compIds)
    {
        /** @var BanDetail $detail */
        foreach ($ban->details as $detail) {
            if (! $detail->deleted_at) {
                if ($detail->ckey === $ckey) {
                    return true;
                }
                if ($detail->ip && in_array($detail->ip, $ips)) {
                    return true;
                }
                if ($detail->comp_id && in_array($detail->comp_id, $compIds)) {
                    return true;
                }
            }
        }

        return false;
    }
}
