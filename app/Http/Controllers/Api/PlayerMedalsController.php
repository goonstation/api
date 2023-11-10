<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\PlayerMedalResource;
use App\Models\Medal;
use App\Models\Player;
use App\Models\PlayerMedal;
use App\Rules\DateRange;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;

/**
 * @tags Player Medals
 */
class PlayerMedalsController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * List filtered and paginated medals
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<PlayerMedalResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        $request->validate([
            'filters.id' => 'int',
            'filters.player_id' => 'int',
            'filters.medal_id' => 'int',
            /**
             * A date or date range
             *
             * @example 2023/01/30 12:00:00 - 2023/02/01 12:00:00
             */
            'filters.created_at' => new DateRange,
            /**
             * A date or date range
             *
             * @example 2023/01/30 12:00:00 - 2023/02/01 12:00:00
             */
            'filters.updated_at' => new DateRange,
        ]);

        return PlayerMedalResource::collection(
            $this->indexQuery(PlayerMedal::with(['medal']))
        );
    }

    /**
     * Show
     *
     * Show a medal for a player
     */
    public function show(Request $request, Player $player)
    {
        $data = $this->validate($request, [
            'medal' => 'required|string|exists:medals,title',
        ]);

        $medal = Medal::where('title', $data['medal'])->first();

        $playerMedal = PlayerMedal::with('medal')
            ->where('player_id', $player->id)
            ->where('medal_id', $medal->id)
            ->firstOrFail();

        return new PlayerMedalResource($playerMedal);
    }

    /**
     * Add
     *
     * Add a medal for a player
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'player_id' => 'required_without:ckey|nullable|integer|exists:players,id',
            'ckey' => 'required_without:player_id|nullable|alpha_num',
            'medal' => 'required|string|exists:medals,title',
            'round_id' => 'nullable|integer|exists:game_rounds,id',
        ]);

        $medal = Medal::where('title', $data['medal'])->first();

        $playerId = isset($data['player_id']) ? $data['player_id'] : null;
        if (! $playerId && isset($data['ckey'])) {
            $player = Player::where('ckey', $data['ckey'])->first();
            if (! $player) {
                return response()->json(['message' => 'Unable to locate that player'], 400);
            }
            $playerId = $player->id;
        }

        $existingPlayerMedal = PlayerMedal::where('player_id', $playerId)
            ->where('medal_id', $medal->id)
            ->exists();
        if ($existingPlayerMedal) {
            return response()->json(['message' => 'That player already has that medal.'], 409);
        }

        $playerMedal = new PlayerMedal();
        $playerMedal->player_id = $playerId;
        $playerMedal->medal_id = $medal->id;
        $playerMedal->round_id = isset($data['round_id']) ? $data['round_id'] : null;
        $playerMedal->save();

        return new PlayerMedalResource($playerMedal);
    }

    /**
     * Delete
     *
     * Delete a medal for a player
     */
    public function destroy(Request $request)
    {
        $data = $this->validate($request, [
            'player_id' => 'required_without:ckey|nullable|integer|exists:players,id',
            'ckey' => 'required_without:player_id|nullable|alpha_num',
            'medal' => 'required|string|exists:medals,title',
        ]);

        $playerId = isset($data['player_id']) ? $data['player_id'] : null;
        if (! $playerId && isset($data['ckey'])) {
            $player = Player::where('ckey', $data['ckey'])->first();
            if (! $player) {
                return response()->json(['message' => 'Unable to locate that player'], 400);
            }
            $playerId = $player->id;
        }

        $medal = Medal::where('title', $data['medal'])->first();

        PlayerMedal::where('player_id', $playerId)
            ->where('medal_id', $medal->id)
            ->delete();

        return ['message' => 'Medal removed'];
    }
}
