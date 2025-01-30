<?php

namespace App\Http\Resources;

use App\Models\GameBuild;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GameBuild */
class GameBuildResource extends JsonResource
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
            'started_by' => $this->startedBy,
            'branch' => $this->branch,
            'commit' => $this->commit,
            'map_id' => $this->map_id,
            'failed' => $this->failed,
            'cancelled' => $this->cancelled,
            'map_switch' => $this->map_switch,
            'cancelled_by' => $this->cancelledBy,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'ended_at' => $this->ended_at,
            /** @var int */
            'duration' => $this->getAttribute('duration'),
        ];
    }
}
