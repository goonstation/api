<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerNoteResource extends JsonResource
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
            'ckey' => $this->ckey,
            'game_admin_id' => $this->game_admin_id,
            'server_id' => $this->server_id,
            'round_id' => $this->round_id,
            'note' => $this->note,
            'legacy_data' => $this->legacy_data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
