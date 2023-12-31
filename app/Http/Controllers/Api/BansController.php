<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BanRequest;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\BanDetailResource;
use App\Http\Resources\BanResource;
use App\Models\Ban;
use App\Models\BanDetail;
use App\Models\GameAdmin;
use App\Models\Player;
use App\Models\PlayerNote;
use App\Traits\IndexableQuery;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class BansController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<BanResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        return BanResource::collection(
            $this->indexQuery(Ban::withTrashed()
                ->with(['gameAdmin', 'gameRound', 'originalBanDetail']))
        );
    }

    /**
     * Check
     */
    public function check(Request $request)
    {
        $request->validate([
            'ckey' => 'required_without_all:comp_id,ip|string',
            'comp_id' => 'required_without_all:ckey,ip|string',
            'ip' => 'required_without_all:ckey,comp_id|ip',
            'server_id' => 'nullable|string',
        ]);

        $ckey = $request->input('ckey', '');
        $compId = $request->input('comp_id', '');
        $ip = $request->input('ip', '0.0.0.0');
        $serverId = $request->input('server_id');

        /*
        * Criteria:
        * Get all existing regular bans that match any of ckey, compId, ip
        * And apply to all servers, or have the serverId we provide
        * And are permanent, or have yet to expire
        */

        $bans = Ban::with(['gameAdmin', 'details', 'gameRound'])
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
                $query->where('ckey', $ckey)
                    ->orWhere('comp_id', $compId)
                    ->orWhere('ip', $ip);
            })
            ->get();

        return BanResource::collection(new BanResource($bans));
    }

    /**
     * Add
     */
    public function store(BanRequest $request)
    {
        $expiresAt = null;
        if (isset($request['duration'])) {
            $expiresAt = Carbon::now()->addSeconds($request['duration'])->toDateTimeString();
        }

        $gameAdmin = GameAdmin::where('ckey', $request['game_admin_ckey'])->first();

        $player = null;
        $ckey = isset($request['ckey']) ? $request['ckey'] : null;
        if ($ckey) {
            $player = Player::where('ckey', $ckey)->first();
        }

        $ban = new Ban();
        $ban->game_admin_id = $gameAdmin->id;
        $ban->round_id = isset($request['round_id']) ? $request['round_id'] : null;
        $ban->server_id = isset($request['server_id']) ? $request['server_id'] : null;
        $ban->reason = $request['reason'];
        $ban->expires_at = $expiresAt;
        $ban->save();

        $banDetail = new BanDetail();
        $banDetail->ckey = $ckey;
        $banDetail->comp_id = isset($request['comp_id']) ? $request['comp_id'] : null;
        $banDetail->ip = isset($request['ip']) ? $request['ip'] : null;
        $ban->details()->save($banDetail);

        $note = new PlayerNote();
        if ($player) {
            $note->player_id = $player->id;
        } else {
            $note->ckey = $ckey;
        }
        $note->game_admin_id = $gameAdmin->id;
        $note->server_id = isset($request['server_id']) ? $request['server_id'] : null;
        $note->round_id = isset($request['round_id']) ? $request['round_id'] : null;
        $note->note = sprintf(
            'Banned %s. Reason: %s',
            isset($request['duration'])
                ? 'for '.CarbonInterval::seconds($request['duration'])->cascade()->forHumans()
                : 'permanently',
            $request['reason']
        );
        $note->save();

        return new BanResource($ban);
    }

    /**
     * Update
     */
    public function update(BanRequest $request, Ban $ban)
    {
        $gameAdmin = GameAdmin::where('ckey', $request['game_admin_ckey'])->first();
        $player = null;
        $ckey = isset($request['ckey']) ? $request['ckey'] : null;
        if ($ckey) {
            $player = Player::where('ckey', $ckey)->first();
        }

        $newBanDetails = $request->only(['server_id', 'reason']);

        // Ensure the server ID is nulled out if we're being told about it, and it's falsey
        if (isset($request['server_id'])) {
            $newBanDetails['server_id'] = $request['server_id'] ? $request['server_id'] : null;
        }

        if (isset($request['duration'])) {
            // A falsey duration means it's essentially "unset", and thus now a permanent ban
            // Otherwise, the admin is altering how long the ban lasts
            if (! $request['duration']) {
                $newBanDetails['expires_at'] = null;
            } else {
                // Ban is temporary, the duration starts from when the ban was first created
                $newExpiresAt = $ban->created_at->addSeconds($request['duration']);

                // Bans can't expire in the past
                if ($newExpiresAt->lte(Carbon::now())) {
                    return response()->json(['error' => 'The ban cannot expire in the past, please increase the duration.'], 400);
                }

                $newBanDetails['expires_at'] = $newExpiresAt->toDateTimeString();
            }
        }

        $ban->update($newBanDetails);
        $ban->originalBanDetail->update($request->only(['ckey', 'comp_id', 'ip']));

        $note = new PlayerNote();
        if ($player) {
            $note->player_id = $player->id;
        } else {
            $note->ckey = $ckey;
        }
        $note->game_admin_id = $gameAdmin->id;
        $note->server_id = $ban->server_id;
        $note->round_id = $ban->round_id;
        $note->note = sprintf(
            'Edited ban. Duration: %s. Reason: %s. Computer ID: %s. IP: %s',
            $ban->expires_at
                ? $ban->expires_at->longAbsoluteDiffForHumans()
                : 'permanent',
            $request['reason'],
            $ban->originalBanDetail->comp_id,
            $ban->originalBanDetail->ip
        );
        $note->save();

        return new BanResource($ban);
    }

    /**
     * Delete
     */
    public function destroy(Ban $ban)
    {
        $ban->delete();

        return ['message' => 'Ban removed'];
    }

    /**
     * Add ban details
     */
    public function addDetails(Request $request, Ban $ban)
    {
        $data = $this->validate($request, [
            'ckey' => 'required_without_all:comp_id,ip',
            'comp_id' => 'required_without_all:ckey,ip',
            'ip' => 'required_without_all:ckey,comp_id|ip',
        ]);

        $banDetail = new BanDetail();
        $banDetail->ckey = isset($data['ckey']) ? $data['ckey'] : null;
        $banDetail->comp_id = isset($data['comp_id']) ? $data['comp_id'] : null;
        $banDetail->ip = isset($data['ip']) ? $data['ip'] : null;
        $ban->details()->save($banDetail);

        return new BanDetailResource($banDetail);
    }

    /**
     * Remove ban detail
     */
    public function destroyDetail(BanDetail $banDetail)
    {
        $banDetail->delete();

        return ['message' => 'Ban detail removed'];
    }
}
