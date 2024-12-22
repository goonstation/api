<?php

namespace App\Traits;

use App\Http\Requests\GameBuildTestMergeCreateRequest;
use App\Http\Requests\GameBuildTestMergeUpdateRequest;
use App\Http\Resources\GameBuildTestMergeResource;
use App\Models\GameAdmin;
use App\Models\GameBuildTestMerge;

trait ManagesGameBuildTestMerges
{
    private function addTestMerge(GameBuildTestMergeCreateRequest $request)
    {
        $gameAdmin = GameAdmin::where('ckey', ckey($request['game_admin_ckey']))->first();

        $testMerge = new GameBuildTestMerge;
        $testMerge->pr_id = $request['pr_id'];
        $testMerge->server_id = $request['server_id'];
        $testMerge->commit = isset($request['commit']) ? $request['commit'] : null;
        $testMerge->addedBy()->associate($gameAdmin);
        $testMerge->save();

        return new GameBuildTestMergeResource($testMerge);
    }

    private function updateTestMerge(GameBuildTestMergeUpdateRequest $request, GameBuildTestMerge $testMerge)
    {
        $serverId = $request->input('server_id', $testMerge->server_id);
        $prId = $request->input('pr_id', $testMerge->pr_id);
        $exists = GameBuildTestMerge::whereNot('id', $testMerge->id)
            ->where('pr_id', $prId)
            ->where('server_id', $serverId)
            ->exists();
        if ($exists) {
            throw new \Exception('A test merge with that PR ID already exists for that server.');
        }

        foreach ($request->all() as $key => $val) {
            if ($key === 'game_admin_id' || $key === 'game_admin_ckey') {
                continue;
            }
            $testMerge[$key] = $val;
        }

        $gameAdmin = GameAdmin::where('ckey', ckey($request['game_admin_ckey']))->first();

        $testMerge->updatedBy()->associate($gameAdmin);
        $testMerge->save();

        return new GameBuildTestMergeResource($testMerge);
    }
}
