<?php

namespace App\Http\Resources;

use App\Models\PlayerConnection;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerStatsResource extends JsonResource
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
            'ckey' => $this->ckey,
            'key' => $this->key,
            'byond_join_date' => $this->byond_join_date,
            'byond_major' => $this->byond_major,
            'byond_minor' => $this->byond_minor,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'played' => $this->played,
            'played_rp' => $this->played_rp,
            'connected' => $this->connected,
            'connected_rp' => $this->connected_rp,
            'time_played' => $this->time_played,
            /** @var PlayerConnection */
            'latest_connection' => $this->latestConnection,
        ];
    }
}
