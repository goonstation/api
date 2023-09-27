<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\JobBanResource;
use App\Models\GameAdmin;
use App\Models\JobBan;
use App\Traits\IndexableQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @tags Job Bans
 */
class JobBansController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * List filtered and paginated job bans
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<JobBanResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        return JobBanResource::collection(
            $this->indexQuery(JobBan::with(['gameAdmin:id,ckey,name']))
        );
    }

    /**
     * Check
     *
     * Check if a job ban exists for given player and server details
     */
    public function check(Request $request)
    {
        $data = $this->validate($request, [
            'ckey' => 'required',
            'job' => 'required',
            'server_id' => 'nullable|string',
        ]);

        $serverId = isset($data['server_id']) ? $data['server_id'] : null;
        $jobBan = JobBan::getValidJobBans($data['ckey'], $data['job'], $serverId)->first();

        return new JobBanResource($jobBan);
    }

    /**
     * Get for player
     *
     * Get all job bans for a given player and server
     */
    public function getForPlayer(Request $request)
    {
        $data = $this->validate($request, [
            'ckey' => 'required',
            'server_id' => 'nullable|string',
        ]);

        $serverId = isset($data['server_id']) ? $data['server_id'] : null;
        $jobBans = JobBan::getValidJobBans($data['ckey'], null, $serverId)->orderBy('id')->get();

        return JobBanResource::collection(new JobBanResource($jobBans));
    }

    /**
     * Add
     *
     * Add a new job ban
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'game_admin_ckey' => 'exists:game_admins,ckey',
            'round_id' => 'nullable|integer',
            'server_id' => 'nullable|string',
            'ckey' => 'required',
            'job' => 'required',
            'reason' => 'required',
            'duration' => 'nullable|integer',
        ]);

        $serverId = isset($data['server_id']) ? $data['server_id'] : null;

        // Check a ban doesn't already exist for the provided ckey and job
        $existingJobBan = JobBan::getValidJobBans($data['ckey'], $data['job'], $serverId)->first();
        if (! empty($existingJobBan)) {
            return response()->json(['error' => 'The player is already banned from that job on this server.'], 400);
        }

        $expiresAt = null;
        if (isset($data['duration'])) {
            $expiresAt = Carbon::now()->addSeconds($data['duration'])->toDateTimeString();
        }

        $gameAdmin = GameAdmin::where('ckey', $data['game_admin_ckey'])->first();

        $jobBan = new JobBan();
        $jobBan->game_admin_id = $gameAdmin->id;
        $jobBan->round_id = isset($data['round_id']) ? $data['round_id'] : null;
        $jobBan->server_id = $serverId;
        $jobBan->ckey = $data['ckey'];
        $jobBan->banned_from_job = $data['job'];
        $jobBan->reason = $data['reason'];
        $jobBan->expires_at = $expiresAt;
        $jobBan->save();

        return new JobBanResource($jobBan);
    }

    /**
     * Update
     *
     * Update an existing job ban
     */
    public function update(Request $request, JobBan $jobBan)
    {
        $data = $this->validate($request, [
            'server_id' => 'nullable|string',
            'job' => 'required',
            'reason' => 'required',
            'duration' => 'nullable|integer',
        ]);

        $serverId = isset($data['server_id']) ? $data['server_id'] : null;

        // Check another ban doesn't already exist for the provided job
        $existingJobBan = JobBan::getValidJobBans($jobBan->ckey, $data['job'], $serverId)->first();
        if (! empty($existingJobBan) && $jobBan->id !== $existingJobBan->id) {
            return response()->json(['error' => 'The player is already banned from that job on this server.'], 400);
        }

        $newBanDetails = $request->only(['server_id', 'reason']);
        $newBanDetails['banned_from_job'] = $data['job'];

        // Ensure the server ID is nulled out if we're being told about it, and it's falsey
        if (isset($data['server_id'])) {
            $newBanDetails['server_id'] = $serverId;
        }

        if (isset($data['duration'])) {
            // A falsey duration means it's essentially "unset", and thus now a permanent ban
            // Otherwise, the admin is altering how long the ban lasts
            if (! $data['duration']) {
                $newBanDetails['expires_at'] = null;
            } else {
                // Ban is temporary, the duration starts from when the ban was first created
                $newExpiresAt = $jobBan->created_at->addSeconds($data['duration']);

                // Bans can't expire in the past
                if ($newExpiresAt->lte(Carbon::now())) {
                    return response()->json(['error' => 'The ban cannot expire in the past, please increase the duration.'], 400);
                }

                $newBanDetails['expires_at'] = $newExpiresAt->toDateTimeString();
            }
        }

        $jobBan->update($newBanDetails);

        return new JobBanResource($jobBan);
    }

    /**
     * Delete
     *
     * Delete an existing job ban
     */
    public function destroy(JobBan $jobBan)
    {
        $jobBan->delete();

        return ['message' => 'Job ban removed'];
    }
}
