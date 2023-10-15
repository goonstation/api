<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerParticipationResource;
use App\Models\PlayerParticipation;
use Illuminate\Http\Request;

/**
 * @tags Player Participations
 */
class PlayerParticipationsController extends Controller
{
    /**
     * Add
     *
     * Add a player participation for a given round
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required|integer|exists:players,id',
            'round_id' => 'required|integer|exists:game_rounds,id',
        ]);

        $participation = new PlayerParticipation;
        $participation->player_id = $data['player_id'];
        $participation->round_id = $data['round_id'];
        $participation->save();

        return new PlayerParticipationResource($participation);
    }

    /**
     * Add Bulk
     *
     * Add multiple participations at once
     */
    public function storeBulk(Request $request)
    {
        $data = $request->validate([
            'players' => 'required|array|exists:players,id',
            'round_id' => 'required|integer|exists:game_rounds,id',
        ]);

        $insertData = [];
        foreach ($data['players'] as $playerId) {
            $insertData[] = [
                'player_id' => $playerId,
                'round_id' => $data['round_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        PlayerParticipation::insert($insertData);

        return ['message' => 'Added participations'];
    }
}
