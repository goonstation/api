<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\PlayerMetadataResource;
use App\Models\PlayerMetadata;
use App\Rules\DateRange;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @tags Player Metadata
 */
class PlayerMetadataController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * List paginated and filtered player meta data
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<PlayerMetadataResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        $request->validate([
            'filters.id' => 'int',
            'filters.ckey' => 'string',
            'filters.data' => 'string',
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

        return PlayerMetadataResource::collection(
            $this->indexQuery(PlayerMetadata::with('player'))
        );
    }

    /**
     * Add
     *
     * Add player metadata
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required|integer|exists:players,id',
            'data' => 'required|string',
        ]);

        $metadata = new PlayerMetadata();
        $metadata->player_id = $data['player_id'];
        $metadata->data = $data['data'];
        $metadata->save();

        return new PlayerMetadataResource($metadata);
    }

    /**
     * Delete from player
     *
     * Delete all metadata associated with a specific player
     */
    public function destroyByPlayer(string $ckey)
    {
        $entries = PlayerMetadata::whereRelation('player', 'ckey', $ckey)
            ->orWhere('ckey', $ckey);
        $entries->delete();

        return ['message' => 'Metadata removed'];
    }

    /**
     * Delete
     *
     * Delete a specific item of metadata
     */
    public function destroyByData(string $data)
    {
        $entries = PlayerMetadata::where('data', $data);
        $entries->delete();

        return ['message' => 'Metadata removed'];
    }
}
