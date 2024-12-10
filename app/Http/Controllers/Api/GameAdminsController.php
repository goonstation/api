<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\GameAdminResource;
use App\Models\GameAdmin;
use App\Rules\DateRange;
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
        $request->validate([
            'filters.id' => 'int',
            'filters.ckey' => 'string',
            'filters.key' => 'string',
            'filters.rank' => 'string',
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
            'rank' => 'required|exists:game_admin_ranks,id',
        ]);

        $gameAdmin = new GameAdmin;
        $gameAdmin->ckey = $data['ckey'];
        $gameAdmin->name = isset($data['name']) ? $data['name'] : null;
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
            'rank' => 'nullable|exists:game_admin_ranks',
        ]);

        if (! empty($data['ckey'])) {
            $gameAdmin->ckey = $data['ckey'];
        }
        if (! empty($data['name'])) {
            $gameAdmin->name = $data['name'];
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
