<?php

namespace App\Http\Resources;

use App\Models\PlayerParticipation;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PlayerParticipation */
class PlayerParticipationResource extends JsonResource
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
            'job' => $this->job,
            'legacy_data' => $this->legacy_data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
