<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BanRequest;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\BanDetailResource;
use App\Http\Resources\BanResource;
use App\Models\Ban;
use App\Models\BanDetail;
use App\Models\Player;
use App\Rules\DateRange;
use App\Rules\Range;
use App\Traits\IndexableQuery;
use App\Traits\ManagesBans;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class BansController extends Controller
{
    use IndexableQuery, ManagesBans;

    /**
     * List
     *
     * List filtered and paginated bans
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<BanResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        $request->validate([
            'filters.id' => 'int',
            /** @example main1 */
            'filters.server' => 'string',
            'filters.admin_ckey' => 'string',
            'filters.reason' => 'string',
            'filters.original_ban_ckey' => 'string',
            'filters.requires_appeal' => 'boolean',
            /**
             * A value, comparison, or range
             *
             * @example 1 or >= 1 or 1-10
             */
            'filters.details' => new Range,
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
            /**
             * A date or date range
             *
             * @example 2023/01/30 12:00:00 - 2023/02/01 12:00:00
             */
            'filters.expires_at' => new DateRange,
            /**
             * A date or date range
             *
             * @example 2023/01/30 12:00:00 - 2023/02/01 12:00:00
             */
            'filters.deleted_at' => new DateRange,
        ]);

        return BanResource::collection(
            $this->indexQuery(Ban::withTrashed()
                ->with(['gameAdmin', 'gameRound', 'details', 'originalBanDetail']))
        );
    }

    /**
     * Check
     *
     * Check if a ban exists for given player data
     */
    public function check(Request $request)
    {
        $request->validate([
            'ckey' => 'required_without_all:comp_id,ip|string|nullable',
            'comp_id' => 'required_without_all:ckey,ip|string|nullable',
            'ip' => 'required_without_all:ckey,comp_id|ip|nullable',
            'server_id' => 'nullable|string',
        ]);

        $ckey = $request->input('ckey', '');
        $compId = $request->input('comp_id', '');
        $ip = $request->input('ip', '0.0.0.0');
        $serverId = $request->input('server_id');

        /*
        * Criteria:
        * Get any existing regular ban that matches any of ckey, compId, ip
        * And apply to all servers, or have the serverId we provide
        * And are permanent, or have yet to expire
        */

        $ban = Ban::with(['gameAdmin', 'originalBanDetail', 'details', 'gameRound'])
            ->where(function (Builder $query) use ($serverId) {
                // Check if the ban applies to all servers, or the server id we were provided
                $query->whereNull('server_id')
                    ->orWhere('server_id', $serverId);
            })
            ->where(function (Builder $query) {
                // Check the ban is permanent, or has yet to expire
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now()->toDateTimeString());
            })
            ->whereHas('details', function (Builder $query) use ($ckey, $compId, $ip) {
                // Check any of the ban details match the provided player details
                if ($ckey) {
                    $query->where('ckey', $ckey);
                } elseif (! $ckey && $compId) {
                    $query->where('comp_id', $compId);
                } elseif (! $ckey && ! $compId && $ip) {
                    $query->where('ip', $ip);
                }

                if ($ckey && $compId) {
                    $query->orWhere('comp_id', $compId);
                }
                if ($ckey && $ip) {
                    $query->orWhere('ip', $ip);
                }

                if ($compId && $ip) {
                    $query->orWhere('ip', $ip);
                }
            })
            ->orderBy('id', 'desc')
            ->first();

        if (! $ban) {
            abort(404);
        }

        return new BanResource($ban);
    }

    /**
     * Add
     *
     * Add a ban for given player data
     */
    public function store(BanRequest $request)
    {
        return $this->addBan($request);
    }

    /**
     * Update
     *
     * Update an existing ban
     */
    public function update(BanRequest $request, Ban $ban)
    {
        try {
            return $this->updateBan($request, $ban);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete
     *
     * Delete an existing ban
     */
    public function destroy(Ban $ban)
    {
        $ban->delete();

        return ['message' => 'Ban removed'];
    }

    /**
     * Add ban details
     *
     * Add new player details to an existing ban. This should be used when an evasion attempt is detected.
     */
    public function addDetails(Request $request, Ban $ban)
    {
        $data = $this->validate($request, [
            'ckey' => 'required_without_all:comp_id,ip|nullable',
            'comp_id' => 'required_without_all:ckey,ip|nullable',
            'ip' => 'required_without_all:ckey,comp_id|ip|nullable',
        ]);

        $banDetail = new BanDetail();
        $banDetail->ckey = isset($data['ckey']) ? $data['ckey'] : null;
        $banDetail->comp_id = isset($data['comp_id']) ? $data['comp_id'] : null;
        $banDetail->ip = isset($data['ip']) ? $data['ip'] : null;
        $ban->details()->save($banDetail);

        return new BanDetailResource($banDetail);
    }

    /**
     * Remove ban details
     *
     * Remove ban details associated with a ban
     */
    public function destroyDetail(BanDetail $banDetail)
    {
        $banDetail->delete();

        return ['message' => 'Ban detail removed'];
    }
}
