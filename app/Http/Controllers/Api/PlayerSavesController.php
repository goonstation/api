<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerDataResource;
use App\Http\Resources\PlayerSaveResource;
use App\Models\Player;
use App\Models\PlayerData;
use App\Models\PlayerSave;
use Illuminate\Http\Request;

/**
 * @tags Player Saves
 */
class PlayerSavesController extends Controller
{
    /**
     * List data and saves
     *
     * List all data and saves for a player
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required|integer|exists:players,id',
        ]);

        $cdata = PlayerData::where('player_id', $data['player_id'])->get();
        $saves = PlayerSave::where('player_id', $data['player_id'])->get();

        return [
            'data' => [
                'data' => PlayerDataResource::collection(new PlayerDataResource($cdata)),
                'saves' => PlayerSaveResource::collection(new PlayerSaveResource($saves)),
            ],
        ];
    }

    /**
     * Add data
     *
     * Add player data
     */
    public function storeData(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required_without:ckey|integer|exists:players,id',
            'ckey' => 'required_without:player_id|alpha_num',
            'key' => 'required|string',
            'value' => 'nullable',
        ]);

        $playerId = isset($data['player_id']) ? $data['player_id'] : null;
        if (!$playerId) {
            $player = Player::where('ckey', $data['ckey'])->firstOrFail();
            $playerId = $player->id;
        }

        $cdata = PlayerData::updateOrCreate([
            'player_id' => $playerId,
            'key' => $data['key'],
        ], [
            'value' => $data['value']
        ]);

        return new PlayerDataResource($cdata);
    }

    /**
     * Add save
     *
     * Add player save
     */
    public function storeFile(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required|integer|exists:players,id',
            'name' => 'required|string',
            'data' => 'nullable',
        ]);

        if (strlen($data['data']) >= 51200) {
            return response()->json(['message' => 'Savefile exceeds 51200 bytes.'], 413);
        }

        $currentSaves = PlayerSave::where('player_id', $data['player_id'])->count();
        if ($currentSaves >= 15) {
            return response()->json(['message' => 'Your account can only hold 15 savefiles.'], 400);
        }

        $save = PlayerSave::updateOrCreate([
            'player_id' => $data['player_id'],
            'name' => $data['name'],
        ], [
            'data' => $data['data'],
        ]);

        return [
            'data' => new PlayerSaveResource($save),
        ];
    }

    /**
     * Add data in bulk
     *
     * Add multiple entries of player data
     *
     * TODO: Describe json object
     */
    public function storeDataBulk(Request $request)
    {
        $data = $request->validate([
            'data' => 'required|json',
        ]);

        /**
         * [{"player_id": "12", "key": "foo", "value": "hello2"}, {"player_id": "12", "key": "foo2", "value": "bar4"}]
         */
        $bulkData = json_decode($data['data']);
        $dataToUpset = [];
        foreach ($bulkData as $item) {
            if (!$item->player_id || !$item->key) continue;
            $dataToUpset[] = [
                'player_id' => $item->player_id,
                'key' => $item->key,
                'value' => $item->value,
            ];
        }

        PlayerData::upsert($dataToUpset, ['player_id', 'key'], ['value']);

        return ['message' => 'Success'];
    }

    /**
     * Transfer saves
     *
     * Transfer all save files from a player to another
     * WARNING: This overwrites all the saves for the target
     */
    public function transferSaves(Request $request)
    {
        $data = $request->validate([
            'from_ckey' => 'required|alpha_num',
            'to_ckey' => 'required|alpha_num',
        ]);

        // See who we're moving stuff from
        $fromPlayer = Player::where('ckey', $data['from_ckey'])->first();
        if (! $fromPlayer) {
            return response()->json(['message' => 'Unable to find source player'], 404);
        }

        // We allow player creation here so that admins can transfer saves to accounts that haven't connected before
        $toPlayer = Player::firstOrCreate(
            ['ckey' => $data['to_ckey']],
        );

        // Only transfer if there's stuff to move
        if (! PlayerSave::where('player_id', $fromPlayer->id)->exists()) {
            return response()->json(['message' => 'Source player has no saves to transfer'], 400);
        }

        // Delete the target's saves
        PlayerSave::where('player_id', $toPlayer->id)->delete();

        // Move files
        PlayerSave::where('player_id', $fromPlayer->id)
            ->update([
                'player_id' => $toPlayer->id,
            ]);

        return ['message' => 'Saves transferred'];
    }

    /**
     * Delete data
     *
     * Delete data for a player
     */
    public function destroyData(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required|integer|exists:players,id',
            'key' => 'required',
        ]);

        PlayerData::where('player_id', $data['player_id'])
            ->where('key', $data['key'])
            ->delete();

        return ['message' => 'Data removed'];
    }

    /**
     * Delete save
     *
     * Delete a save for a player
     */
    public function destroyFile(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required|integer|exists:players,id',
            'name' => 'required',
        ]);

        PlayerSave::where('player_id', $data['player_id'])
            ->where('name', $data['name'])
            ->delete();

        return ['message' => 'Save removed'];
    }
}
