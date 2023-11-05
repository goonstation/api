<?php

namespace App\Http\Resources;

use App\Models\Medal;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerMedalResource extends JsonResource
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
            'medal_id' => $this->medal_id,
            /** @var Medal */
            'medal' => $this->whenLoaded('medal'),
            'round_id' => $this->round_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
