<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerAntagResource extends JsonResource
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
            'antag_role' => $this->antag_role,
            'late_join' => $this->late_join,
            'weight_exempt' => $this->weight_exempt,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
