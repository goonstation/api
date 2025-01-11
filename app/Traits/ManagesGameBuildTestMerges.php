<?php

namespace App\Traits;

use App\Http\Requests\GameBuildTestMergeCreateRequest;
use App\Http\Requests\GameBuildTestMergeUpdateRequest;
use App\Http\Resources\GameBuildTestMergeResource;
use App\Models\GameAdmin;
use App\Models\GameBuildSetting;
use App\Models\GameBuildTestMerge;

trait ManagesGameBuildTestMerges
{
    private function addTestMerge(GameBuildTestMergeCreateRequest $request)
    {
        $gameAdmin = GameAdmin::where('ckey', ckey($request['game_admin_ckey']))->first();
        $servers = $request->input('server_ids', [$request->input('server_id')]);
        $buildSettings = GameBuildSetting::whereIn('server_id', $servers)->get();
        if ($buildSettings->isEmpty()) {
            if (count($servers) === 1) {
                throw new \Exception("No build settings exist for server {$servers[0]}.");
            } else {
                throw new \Exception('No build settings exist for those servers.');
            }
        }

        foreach ($servers as $server) {
            $setting = $buildSettings->firstWhere('server_id', $server);
            if (! $setting) {
                throw new \Exception("No build settings exist for server $server.");
            }

            $exists = GameBuildTestMerge::where('pr_id', $request['pr_id'])->where('setting_id', $setting->id)->exists();
            if ($exists) {
                throw new \Exception("A test merge with that PR ID already exists for server $server.");
            }
        }

        $testMerges = collect();
        foreach ($buildSettings as $setting) {
            $testMerge = new GameBuildTestMerge;
            $testMerge->pr_id = $request['pr_id'];
            $testMerge->commit = isset($request['commit']) ? $request['commit'] : null;
            $testMerge->buildSettings()->associate($setting);
            $testMerge->addedBy()->associate($gameAdmin);
            $testMerge->save();
            $testMerges->add($testMerge);
        }

        return GameBuildTestMergeResource::collection($testMerges);
    }

    private function updateTestMerge(GameBuildTestMergeUpdateRequest $request, GameBuildTestMerge $testMerge)
    {
        if ($request->has('pr_id') || $request->has('server_id')) {
            $prId = $request->input('pr_id', $testMerge->pr_id);

            $settingId = $testMerge->setting_id;

            if ($request->has('server_id')) {
                $newBuildSetting = GameBuildSetting::firstWhere('server_id', $request['server_id']);
                if (! $newBuildSetting) {
                    throw new \Exception('No build settings exist for that server.');
                }
                $settingId = $newBuildSetting->id;
                $request = $request->merge(['setting_id' => $settingId]);
            }

            $exists = GameBuildTestMerge::whereNot('id', $testMerge->id)
                ->where('pr_id', $prId)
                ->where('setting_id', $settingId)
                ->exists();

            if ($exists) {
                throw new \Exception('A test merge with that PR ID already exists for that server.');
            }
        }

        foreach ($request->all() as $key => $val) {
            if ($key === 'game_admin_id' || $key === 'game_admin_ckey' || $key === 'server_id') {
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
