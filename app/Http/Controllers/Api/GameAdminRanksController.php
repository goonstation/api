<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\GameAdminRankResource;
use App\Models\GameAdminRank;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class GameAdminRanksController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * List paginated and filtered game admin ranks
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<GameAdminRankResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        return GameAdminRankResource::collection(
            $this->indexQuery(GameAdminRank::class)
        );
    }

    /**
     * Add
     *
     * Add a new game admin rank
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'rank' => 'required|string',
        ]);

        $gameAdminRank = new GameAdminRank();
        $gameAdminRank->rank = $data['rank'];
        $gameAdminRank->save();

        return new GameAdminRankResource($gameAdminRank);
    }

    /**
     * Update
     *
     * Update an existing game admin rank
     */
    public function update(Request $request, GameAdminRank $gameAdminRank)
    {
        $data = $request->validate([
            'rank' => 'required|string',
        ]);

        $gameAdminRank->rank = $data['rank'];
        $gameAdminRank->save();

        return new GameAdminRankResource($gameAdminRank);
    }

    /**
     * Delete
     *
     * Delete an existing game admin rank
     */
    public function destroy(GameAdminRank $gameAdminRank)
    {
        $gameAdminRank->delete();

        return ['message' => 'Game admin rank deleted'];
    }
}
