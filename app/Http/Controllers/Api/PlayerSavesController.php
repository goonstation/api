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
            'player_id' => 'required|integer|exists:players,id',
            'key' => 'required|string',
            'value' => 'required',
        ]);

        PlayerData::where([
            'player_id' => $data['player_id'],
            'key' => $data['key'],
        ])->delete();

        $cdata = new PlayerData();
        $cdata->player_id = $data['player_id'];
        $cdata->key = $data['key'];
        $cdata->value = $data['value'];
        $cdata->save();

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
            'data' => 'required|string',
        ]);

        if (strlen($data['data']) >= 51200) {
            return response()->json(['error' => 'Savefile exceeds 51200 bytes.'], 413);
        }

        $currentSaves = PlayerSave::where('player_id', $data['player_id'])->count();
        if ($currentSaves >= 15) {
            return response()->json(['error' => 'Your account can only hold 15 savefiles.'], 400);
        }

        PlayerSave::where([
            'player_id' => $data['player_id'],
            'name' => $data['name'],
        ])->delete();

        $save = new PlayerSave();
        $save->player_id = $data['player_id'];
        $save->name = $data['name'];
        $save->data = $data['data'];
        $save->save();

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

        // See who we're moving stuff to
        $toPlayer = Player::where('ckey', $data['to_ckey'])->first();
        if (! $toPlayer) {
            return response()->json(['message' => 'Unable to find target player'], 404);
        }

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
