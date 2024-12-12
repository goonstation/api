<?php

namespace App\Http\Resources;

use App\Models\GameRound;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GameRound */
class GameRoundResource extends JsonResource
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
            'map' => $this->map,
            'game_type' => $this->game_type,
            'rp_mode' => $this->rp_mode,
            'test_merges' => $this->test_merges,
            'crashed' => $this->crashed,
            'ended_at' => $this->ended_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
