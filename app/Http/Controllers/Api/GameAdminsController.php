<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\GameAdminResource;
use App\Models\GameAdmin;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @tags Game Admins
 */
class GameAdminsController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * List paginated and filtered game admins
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<GameAdminResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        return GameAdminResource::collection(
            $this->indexQuery(GameAdmin::class)
        );
    }

    /**
     * Add
     *
     * Add a new game admin
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ckey' => 'required|string|unique:game_admins,ckey',
            'name' => 'nullable|string',
            'discord_id' => 'nullable|string',
            'rank' => 'required|exists:game_admin_ranks,id',
        ]);

        $gameAdmin = new GameAdmin();
        $gameAdmin->ckey = $data['ckey'];
        $gameAdmin->name = isset($data['name']) ? $data['name'] : null;
        $gameAdmin->discord_id = isset($data['discord_id']) ? $data['discord_id'] : null;
        $gameAdmin->rank_id = $data['rank'];
        $gameAdmin->save();

        return new GameAdminResource($gameAdmin);
    }

    /**
     * Update
     *
     * Update an existing game admin
     */
    public function update(Request $request, GameAdmin $gameAdmin)
    {
        $data = $request->validate([
            'ckey' => 'nullable|string|unique:game_admins,ckey',
            'name' => 'nullable|string',
            'discord_id' => 'nullable|string',
            'rank' => 'nullable|exists:game_admin_ranks',
        ]);

        if (! empty($data['ckey'])) {
            $gameAdmin->ckey = $data['ckey'];
        }
        if (! empty($data['name'])) {
            $gameAdmin->name = $data['name'];
        }
        if (! empty($data['discord_id'])) {
            $gameAdmin->discord_id = $data['discord_id'];
        }
        if (! empty($data['rank'])) {
            $gameAdmin->rank_id = $data['rank'];
        }
        $gameAdmin->save();

        return new GameAdminResource($gameAdmin);
    }

    /**
     * Delete
     *
     * Delete an existing game admin
     */
    public function destroy(GameAdmin $gameAdmin)
    {
        $gameAdmin->delete();

        return ['message' => 'Game admin deleted'];
    }
}
