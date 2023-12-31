<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\PlayerNoteResource;
use App\Models\GameAdmin;
use App\Models\Player;
use App\Models\PlayerNote;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;

class PlayerNotesController extends Controller
{
    use IndexableQuery;

    /**
     * Get list
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<PlayerNoteResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        return PlayerNoteResource::collection(
            $this->indexQuery(PlayerNote::class)
        );
    }

    /**
     * Add
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'game_admin_ckey' => 'exists:game_admins,ckey',
            'round_id' => 'nullable|integer',
            'server_id' => 'nullable|string',
            'ckey' => 'required',
            'note' => 'required',
        ]);

        $gameAdmin = null;
        if (isset($data['game_admin_ckey'])) {
            $gameAdmin = GameAdmin::where('ckey', $data['game_admin_ckey'])->first();
        }

        $player = Player::where('ckey', $data['ckey'])->first();

        $note = new PlayerNote();
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

    /**
     * Update
     */
    public function update(Request $request, PlayerNote $note)
    {
        $data = $this->validate($request, [
            'game_admin_ckey' => 'exists:game_admins,ckey',
            'server_id' => 'nullable|string',
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

        $updateData['server_id'] = $data['server_id'];
        $updateData['note'] = $data['note'];
        $note->update($updateData);

        return new PlayerNoteResource($note);
    }

    /**
     * Delete
     */
    public function destroy(PlayerNote $note)
    {
        $note->delete();

        return ['message' => 'Note removed'];
    }
}
