<?php

namespace App\Traits;

use App\Http\Requests\BanRequest;
use App\Http\Resources\BanResource;
use App\Models\Ban;
use App\Models\BanDetail;
use App\Models\GameAdmin;
use App\Models\Player;
use App\Models\PlayerNote;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;

trait ManagesBans
{
    private function banHistory($ckey, $compIds, $ips)
    {
        $bans = Ban::with([
            'gameAdmin',
            'gameServer',
            'originalBanDetail',
            'details',
            'gameRound',
        ])
            ->withTrashed()
            ->whereHas('details', function ($query) use ($ckey, $compIds, $ips) {
                $query->withTrashed();
                // Check any of the ban details match the provided player details
                if ($ckey) {
                    $query->where('ckey', $ckey);
                } elseif (! $ckey && $compIds) {
                    $query->whereIn('comp_id', $compIds);
                } elseif (! $ckey && ! $compIds && $ips) {
                    $query->whereIn('ip', $ips);
                }

                if ($ckey && $compIds) {
                    $query->orWhereIn('comp_id', $compIds);
                }
                if ($ckey && $ips) {
                    $query->orWhereIn('ip', $ips);
                }
            })
            ->orderBy('id', 'desc')
            ->get();

        return $bans;
    }

    /**
     * Add a ban
     */
    private function addBan(BanRequest $request)
    {
        $expiresAt = null;
        if (isset($request['duration'])) {
            $duration = (int) $request['duration'];
            if ($duration > 0) {
                $expiresAt = Carbon::now()->addSeconds($duration)->toDateTimeString();
            }
        }

        $gameAdmin = GameAdmin::where('ckey', ckey($request['game_admin_ckey']))->first();

        $player = null;
        $ckey = isset($request['ckey']) ? ckey($request['ckey']) : null;
        if ($ckey) {
            $player = Player::where('ckey', $ckey)->first();
        }

        $serverId = isset($request['server_id']) ? $request['server_id'] : null;
        if ($serverId === 'all') {
            $serverId = null;
        }

        $ban = new Ban();
        $ban->game_admin_id = $gameAdmin->id;
        $ban->round_id = isset($request['round_id']) ? $request['round_id'] : null;
        $ban->server_id = $serverId;
        $ban->reason = $request['reason'];
        $ban->expires_at = $expiresAt;
        $ban->requires_appeal = isset($request['requires_appeal']) ? (bool) $request['requires_appeal'] : false;
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
        $note->server_id = $serverId;
        $note->round_id = isset($request['round_id']) ? $request['round_id'] : null;
        $note->note = sprintf(
            'Banned from %s %s. Reason: %s',
            is_null($serverId) ? 'all servers' : $serverId,
            isset($request['duration']) && (int) $request['duration'] > 0
                ? 'for '.CarbonInterval::seconds($request['duration'])->cascade()->forHumans()
                : 'permanently',
            $request['reason']
        );
        $note->save();

        $ban->gameAdmin = $gameAdmin;

        return new BanResource($ban);
    }

    /**
     * Update an existing ban
     */
    private function updateBan(BanRequest $request, Ban $ban)
    {
        if ($ban->deleted_at) {
            throw new Exception('This ban has already been removed.');
        }
        if ($ban->expires_at && $ban->expires_at->lte(Carbon::now())) {
            throw new Exception('This ban has already expired.');
        }

        $gameAdmin = GameAdmin::where('ckey', ckey($request['game_admin_ckey']))->first();
        $player = null;
        $ckey = isset($request['ckey']) ? ckey($request['ckey']) : null;
        if ($ckey) {
            $player = Player::where('ckey', $ckey)->first();
        }

        $newBanDetails = $request->only(['server_id', 'reason', 'requires_appeal']);

        // Ensure the server ID is nulled out if we're being told about it, and it's falsey
        if (isset($request['server_id'])) {
            $newBanDetails['server_id'] = $request['server_id'] ? $request['server_id'] : null;
            if ($newBanDetails['server_id'] === 'all') {
                $newBanDetails['server_id'] = null;
            }
        }

        if (isset($request['duration'])) {
            // A falsey duration means it's essentially "unset", and thus now a permanent ban
            // Otherwise, the admin is altering how long the ban lasts
            if (! $request['duration']) {
                $newBanDetails['expires_at'] = null;
            } else {
                // Ban is temporary, the new duration shall apply from right now
                // This is so we can add or reduce the duration if necessary
                $newExpiresAt = Carbon::now()->addSeconds($request['duration']);

                // Bans can't expire in the past
                if ($newExpiresAt->lte(Carbon::now())) {
                    throw new Exception('The ban cannot expire in the past, please increase the duration.');
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
            'Edited ban. Server: %s. Duration: %s. Reason: %s. Computer ID: %s. IP: %s',
            is_null($ban->server_id) ? 'all servers' : $ban->server_id,
            $ban->expires_at
                ? $ban->expires_at->longAbsoluteDiffForHumans()
                : 'permanent',
            $request['reason'],
            $ban->originalBanDetail->comp_id,
            $ban->originalBanDetail->ip
        );
        $note->save();

        $ban->gameAdmin = $gameAdmin;

        return new BanResource($ban->load('originalBanDetail'));
    }
}
