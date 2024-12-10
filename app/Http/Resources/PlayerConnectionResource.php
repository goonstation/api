<?php

namespace App\Http\Resources;

use App\Models\PlayerConnection;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PlayerConnection */
class PlayerConnectionResource extends JsonResource
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
            'player_id' => $this->player_id,
            'round_id' => $this->round_id,
            'ip' => $this->ip,
            'comp_id' => $this->comp_id,
            'legacy_data' => $this->legacy_data,
            'country' => $this->country,
            'country_iso' => $this->country_iso,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
