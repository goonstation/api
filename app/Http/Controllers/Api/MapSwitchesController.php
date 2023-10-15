<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MapSwitchResource;
use App\Models\GameAdmin;
use App\Models\MapSwitch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * @tags Map Switches
 */
class MapSwitchesController extends Controller
{
    /**
     * Add
     *
     * Trigger a map switch for a given server
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'game_admin_ckey' => 'nullable|string',
            'server_id' => 'nullable|string',
            'round_id' => 'required|integer',
            'map' => 'required|string',
            'votes' => 'nullable|integer',
        ]);

        $gameAdmin = null;
        if (isset($data['game_admin_ckey'])) {
            $gameAdmin = GameAdmin::where('ckey', $data['game_admin_ckey'])->first();
        }

        $mapSwitch = new MapSwitch();
        if ($gameAdmin) {
            $mapSwitch->game_admin_id = $gameAdmin->id;
        }
        $mapSwitch->round_id = $data['round_id'];
        $mapSwitch->server_id = isset($data['server_id']) ? $data['server_id'] : null;
        $mapSwitch->map = $data['map'];
        $mapSwitch->votes = isset($data['votes']) ? $data['votes'] : 0;
        $mapSwitch->save();

        $res = Http::withHeaders([
            'Api-Key' => config('goonhub-ci.api_key'),
            'Content-Type' => 'application/json',
        ])
            ->post(
                config('goonhub-ci.url').'/switch-map',
                [
                    'map' => $mapSwitch->map,
                    'server' => $mapSwitch->server_id,
                ]
            );

        return [
            'data' => [
                'map_switch' => new MapSwitchResource($mapSwitch),
                /** @var int HTTP status code response from the build server */
                'status' => $res->status(),
            ],
        ];
    }
}
