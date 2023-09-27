<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameRoundResource;
use App\Models\GameRound;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @tags Game Rounds
 */
class GameRoundsController extends Controller
{
    /**
     * Add
     *
     * Start a new game round
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'server_id' => 'required|string',
            'map' => 'required|string',
            'rp_mode' => 'nullable|boolean',
        ]);

        $gameRound = new GameRound;
        $gameRound->server_id = $data['server_id'];
        $gameRound->map = $data['map'];
        $gameRound->rp_mode = empty($data['rp_mode']) ? false : $data['rp_mode'];
        $gameRound->save();

        return new GameRoundResource($gameRound);
    }

    /**
     * Update
     *
     * Update a game round. This should be used when game round data we care about is set after the start of the round.
     */
    public function update(Request $request, GameRound $gameRound)
    {
        $data = $request->validate([
            'game_type' => 'string',
        ]);
        $gameRound->game_type = isset($data['game_type']) ? $data['game_type'] : null;
        $gameRound->update();

        return new GameRoundResource($gameRound);
    }

    /**
     * End
     *
     * End a game round.
     */
    public function endRound(Request $request, GameRound $gameRound)
    {
        $data = $request->validate([
            'crashed' => 'boolean',
        ]);
        $gameRound->crashed = isset($data['crashed']) ? (bool) $data['crashed'] : false;
        $gameRound->ended_at = Carbon::now();
        $gameRound->update();

        return new GameRoundResource($gameRound);
    }
}
