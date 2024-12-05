<?php

namespace App\Traits;

use App\Http\Resources\PlayerNoteResource;
use App\Models\GameAdmin;
use App\Models\Player;
use App\Models\PlayerNote;
use Illuminate\Http\Request;

trait ManagesPlayerNotes
{
    private function addNote(Request $request)
    {
        $data = $request->validate([
            'game_admin_ckey' => 'exists:game_admins,ckey',
            'round_id' => 'nullable|integer|exists:game_rounds,id',
            'server_id' => 'nullable|string|exists:game_servers,server_id',
            'ckey' => 'required',
            'note' => 'required',
        ]);

        $gameAdmin = null;
        if (isset($data['game_admin_ckey'])) {
            $gameAdmin = GameAdmin::where('ckey', $data['game_admin_ckey'])->first();
        }

        $player = Player::where('ckey', $data['ckey'])->first();

        $note = new PlayerNote;
        $note->game_admin_id = $gameAdmin ? $gameAdmin->id : null;
        $note->round_id = isset($data['round_id']) ? $data['round_id'] : null;
        $note->server_id = isset($data['server_id']) ? $data['server_id'] : null;
        if ($player) {
            $note->player_id = $player->id;
        } else {
            $note->ckey = $data['ckey'];
        }
        $note->note = $data['note'];
        $note->save();

        return new PlayerNoteResource($note);
    }

    private function updateNote(Request $request, PlayerNote $note)
    {
        $data = $request->validate([
            'game_admin_ckey' => 'exists:game_admins,ckey',
            'server_id' => 'nullable|string|exists:game_servers,server_id',
            'ckey' => 'required',
            'note' => 'required',
        ]);

        $updateData = [];
        if (isset($data['game_admin_ckey'])) {
            $gameAdmin = GameAdmin::where('ckey', $data['game_admin_ckey'])->first();
            if ($gameAdmin) {
                $updateData['game_admin_id'] = $gameAdmin->id;
            }
        }

        $player = Player::where('ckey', $data['ckey'])->first();
        if ($player) {
            $updateData['player_id'] = $player->id;
        } else {
            $updateData['ckey'] = $data['ckey'];
        }

        if (isset($data['server_id'])) {
            $updateData['server_id'] = $data['server_id'];
        }
        $updateData['note'] = $data['note'];
        $note->update($updateData);

        return new PlayerNoteResource($note);
    }
}
