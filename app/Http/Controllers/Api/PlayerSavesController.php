<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerDataResource;
use App\Http\Resources\PlayerSaveResource;
use App\Models\PlayerData;
use App\Models\PlayerSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return new PlayerSaveResource($save);
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

        $bulkData = json_decode($data['data']);
        foreach ($bulkData as $playerId => $dataNames) {
            foreach ($dataNames as $dataName => $operation) {
                if ($operation->command == 'add') {
                    DB::update(
                        'UPDATE player_data SET value = (CAST(value AS INTEGER) + CAST(:value AS INTEGER)) WHERE player_id = :player_id AND key = :key',
                        [
                            'player_id' => $playerId,
                            'key' => $dataName,
                            'value' => $operation->value,
                        ]
                    );
                } elseif ($operation->command == 'replace') {
                    PlayerData::where([
                        'player_id' => $playerId,
                        'key' => $dataName,
                    ])->delete();

                    $cdata = new PlayerData();
                    $cdata->player_id = $playerId;
                    $cdata->key = $dataName;
                    $cdata->value = $operation->value;
                    $cdata->save();
                }
            }
        }

        return ['message' => 'Success'];
    }

    /**
     * Delete data
     *
     * Delete data for a player
     */
    public function destroyData(PlayerData $playerData)
    {
        $playerData->delete();

        return ['message' => 'Data removed'];
    }

    /**
     * Delete save
     *
     * Delete a save for a player
     */
    public function destroyFile(PlayerSave $playerSave)
    {
        $playerSave->delete();

        return ['message' => 'Save removed'];
    }
}
