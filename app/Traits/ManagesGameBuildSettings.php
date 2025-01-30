<?php

namespace App\Traits;

use App\Http\Requests\GameBuildSettingCreateRequest;
use App\Http\Requests\GameBuildSettingUpdateRequest;
use App\Http\Resources\GameBuildSettingResource;
use App\Models\GameBuildSetting;

trait ManagesGameBuildSettings
{
    private function addSetting(GameBuildSettingCreateRequest $request)
    {
        $setting = new GameBuildSetting;
        $setting->server_id = $request['server_id'];
        $setting->branch = $request['branch'];
        $setting->byond_major = $request['byond_major'];
        $setting->byond_minor = $request['byond_minor'];
        $setting->rustg_version = $request['rustg_version'];
        $setting->rp_mode = isset($request['rp_mode']) ? $request['rp_mode'] : false;
        $setting->map_id = isset($request['map_id']) ? $request['map_id'] : null;
        $setting->save();

        return new GameBuildSettingResource($setting);
    }

    private function updateSetting(GameBuildSettingUpdateRequest $request, GameBuildSetting $setting)
    {
        foreach ($request->all() as $key => $val) {
            $setting[$key] = $val;
        }

        $setting->save();

        return new GameBuildSettingResource($setting);
    }
}
