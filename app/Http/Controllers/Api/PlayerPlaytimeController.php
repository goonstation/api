<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerPlaytimeController extends Controller
{
    /**
     * Add playtime in bulk
     */
    public function storeBulk(Request $request)
    {
        /* e.g.
        players = [
            [
                id: 123,
                seconds_played: 456
            ]
        ]
        */
        $data = $request->validate([
            'players' => 'required|array',
            'players.*.id' => 'required|integer|exists:players,id',
            'players.*.seconds_played' => 'required|integer',
            'server_id' => 'required|string',
        ]);

        $values = [];
        $valuesSql = '';
        foreach ($data['players'] as $key => $player) {
            array_push($values, $player['id'], $player['seconds_played'], $data['server_id']);
            $valuesSql .= '(?, ?, ?), ';
        }

        $valuesSql = substr($valuesSql, 0, -2);
        DB::insert("
            INSERT INTO player_playtime (player_id, seconds_played, server_id)
            VALUES $valuesSql
            ON CONFLICT ON CONSTRAINT player_playtime_player_id_server_id_unique
            DO UPDATE SET seconds_played = player_playtime.seconds_played + EXCLUDED.seconds_played;
        ", $values);

        return ['message' => 'Success'];
    }
}
