<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\PlayerSearchResource;
use App\Http\Resources\PlayerStatsResource;
use App\Jobs\RecordPlayerConnection;
use App\Models\Player;
use App\Models\PlayerConnection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlayersController extends Controller
{
    /**
     * Query byond for the join date of an account
     *
     * @param  string  $ckey
     * @return string|null
     */
    private function getByondJoinDate(string $ckey)
    {
        $response = Http::get("https://secure.byond.com/members/$ckey?format=text");
        if ($response->failed()) {
            return null;
        }
        preg_match('/joined = "(.*)"/i', $response->body(), $matches);
        if (empty($matches[1])) {
            return null;
        }

        return $matches[1];
    }

    /**
     * Add a login
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ckey' => 'required|alpha_num',
            'key' => 'required|string',
            'ip' => 'required|ipv4',
            'comp_id' => 'required|integer',
            'byond_major' => 'nullable|integer',
            'byond_minor' => 'nullable|integer',
            'round_id' => 'nullable|integer|exists:game_rounds,id',
        ]);

        $player = Player::where('ckey', $data['ckey'])->first();
        if (is_null($player)) {
            $player = new Player();
            $player->ckey = $data['ckey'];
        }

        if (empty($player->key)) {
            $player->key = $data['key'];
        }

        if (empty($player->byond_join_date)) {
            $player->byond_join_date = $this->getByondJoinDate($data['ckey']);
        }

        if (isset($data['byond_major'])) {
            $player->byond_major = $data['byond_major'];
        }
        if (isset($data['byond_minor'])) {
            $player->byond_minor = $data['byond_minor'];
        }
        $player->save();

        RecordPlayerConnection::dispatch($player->id, $data);

        return new PlayerResource($player);
    }

    /**
     * Search for a player
     *
     * One or multiple of either ckey, ip, or comp_id required
     */
    public function search(Request $request)
    {
        $data = $request->validate([
            'ckey' => 'required_without_all:ip,comp_id|alpha_num',
            'ip' => 'required_without_all:ckey,comp_id|ipv4',
            'comp_id' => 'required_without_all:ckey,ip|integer',
            'exact' => 'nullable|boolean',
        ]);

        $exact = isset($data['exact']) && $data['exact'];

        $conns = collect();
        $playerConns = collect();
        $connFiltered = false;
        $conn = PlayerConnection::with('player:id,ckey')
            ->select('id', 'ip', 'comp_id', 'player_id', 'created_at');

        if (isset($data['ckey'])) {
            $ckey = $data['ckey'];
            $players = Player::with([
                'connections' => function ($query) {
                    $query->select('id', 'ip', 'comp_id', 'player_id', 'created_at')
                        ->distinct('ip', 'comp_id', 'player_id');
                },
            ]);

            if ($exact) {
                $players = $players->where('ckey', $ckey);
            } else {
                $players->where('ckey', 'LIKE', "%$ckey%");
            }
            $players = $players->get();

            // Create a shallower connections array, while also normalizing data by moving the ckey
            // field up to the root object for each connection
            foreach ($players as $player) {
                $pcs = $player->connections->toArray();
                foreach ($pcs as $key => $pc) {
                    $pcs[$key]['ckey'] = $player->ckey;
                }
                $playerConns = $playerConns->merge($pcs);
            }
        }
        if (isset($data['ip'])) {
            $ip = $data['ip'];
            if ($exact) {
                $conn = $conn->orWhere('ip', $ip);
            } else {
                $conn = $conn->orWhere('ip', 'LIKE', "%$ip%");
            }
            $connFiltered = true;
        }
        if (isset($data['comp_id'])) {
            $compId = $data['comp_id'];
            if ($exact) {
                $conn = $conn->orWhere('comp_id', $compId);
            } else {
                $conn = $conn->orWhere('comp_id', 'LIKE', "%$compId%");
            }
            $connFiltered = true;
        }

        if ($connFiltered) {
            $conn = $conn->distinct('ip', 'comp_id', 'player_id')->get();
            // Normalize data by moving ckey field up to the root object for each connection
            $conn = $conn->toArray();
            foreach ($conn as $key => $c) {
                $conn[$key]['ckey'] = $c['player']['ckey'];
                unset($conn[$key]['player']);
            }
            $conns = $conns->merge($conn);
        }
        if ($playerConns) {
            $conns = $conns->merge($playerConns);
        }

        // If we have data from both player and player_connections, we might have duplicates
        // So we need this unique call in addition to the prior distinct calls
        $conns = $conns->unique(function (array $item) {
            return $item['ip'].$item['comp_id'].$item['ckey'];
        });

        $conns = $conns->sortByDesc('id');

        return PlayerSearchResource::collection(
            new PlayerSearchResource($conns)
        );
    }

    /**
     * Get player stats
     */
    public function stats(Request $request)
    {
        $data = $request->validate([
            'ckey' => 'required|alpha_num',
        ]);

        $stats = Player::with('latestConnection')
            // Ok so I started out wanting to make this "eloquent-y" but this looks like hot garbage
            ->withCount([
                'participations as played' => function (Builder $query) {
                    $query->where(function (Builder $query) {
                        $query->where('round_id', null)->whereNot('legacy_data->rp_mode', true);
                    })
                        ->orWhere(function (Builder $query) {
                            $query->whereNot('round_id', null)->whereRelation('gameRound', 'rp_mode', '=', false);
                        });
                },
                'participations as played_rp' => function (Builder $query) {
                    $query->where(function (Builder $query) {
                        $query->where('round_id', null)->where('legacy_data->rp_mode', true);
                    })
                        ->orWhere(function (Builder $query) {
                            $query->whereNot('round_id', null)->whereRelation('gameRound', 'rp_mode', '=', true);
                        });
                },
                'connections as connected' => function (Builder $query) {
                    $query->where(function (Builder $query) {
                        $query->where('round_id', null)->whereNot('legacy_data->rp_mode', true);
                    })
                        ->orWhere(function (Builder $query) {
                            $query->whereNot('round_id', null)->whereRelation('gameRound', 'rp_mode', '=', false);
                        });
                },
                'connections as connected_rp' => function (Builder $query) {
                    $query->where(function (Builder $query) {
                        $query->where('round_id', null)->where('legacy_data->rp_mode', true);
                    })
                        ->orWhere(function (Builder $query) {
                            $query->whereNot('round_id', null)->whereRelation('gameRound', 'rp_mode', '=', true);
                        });
                },
            ])
            ->selectRaw(
                '(SELECT COALESCE(SUM(seconds_played), 0) FROM player_playtime where players.id = player_playtime.player_id) AS time_played'
            )
            ->where('ckey', $data['ckey'])
            ->first();

        return new PlayerStatsResource($stats);
    }
}
