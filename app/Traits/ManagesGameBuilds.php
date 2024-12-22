<?php

namespace App\Traits;

use App\Http\Requests\GameBuildCancelRequest;
use App\Http\Requests\GameBuildCreateRequest;
use App\Jobs\GameBuild as GameBuildJob;
use App\Libraries\GameBuilder\Build as GameBuildBuild;
use App\Models\GameAdmin;
use App\Models\GameBuildSetting;
use App\Models\GameServer;

trait ManagesGameBuilds
{
    private function addBuild(GameBuildCreateRequest $request)
    {
        $admin = GameAdmin::where('ckey', $request['game_admin_ckey'])->firstOrFail();
        $server = GameServer::where('server_id', $request['server_id'])->firstOrFail();
        $setting = GameBuildSetting::where('server_id', $request['server_id'])->firstOrFail();

        $mapSwitch = false;
        if (! empty($request['map'])) {
            $mapSwitch = true;
            $setting->map_id = $request['map'];
            $setting->save();
        }

        GameBuildJob::dispatch($admin, $server, $mapSwitch);
    }

    private function cancelBuild(GameBuildCancelRequest $request)
    {
        if (! GameBuildJob::isBuilding($request['server_id'])) {
            return abort(404, 'No build in process');
        }

        $admin = GameAdmin::where('ckey', $request['game_admin_ckey'])->firstOrFail();
        GameBuildBuild::cancel($request['server_id'], $admin->id);
    }
}
