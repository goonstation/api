<?php

namespace App\Http\Resources;

use App\Models\GameBuildSetting;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GameBuildSetting */
class GameBuildSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'server_id' => $this->server_id,
            'branch' => $this->branch,
            'byond_major' => $this->byond_major,
            'byond_minor' => $this->byond_minor,
            'rustg_version' => $this->rustg_version,
            'rp_mode' => $this->rp_mode,
            'map_id' => $this->map_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
