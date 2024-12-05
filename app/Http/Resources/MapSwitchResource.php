<?php

namespace App\Http\Resources;

use App\Models\MapSwitch;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MapSwitch */
class MapSwitchResource extends JsonResource
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
            'game_admin_id' => $this->game_admin_id,
            'round_id' => $this->round_id,
            'server_id' => $this->server_id,
            'map' => $this->map,
            'votes' => $this->votes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
