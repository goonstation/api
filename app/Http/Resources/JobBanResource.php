<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobBanResource extends JsonResource
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
            'round_id' => $this->round_id,
            'game_admin_id' => $this->game_admin_id,
            'server_id' => $this->server_id,
            'ckey' => $this->ckey,
            'banned_from_job' => $this->banned_from_job,
            'reason' => $this->reason,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            /** @var array{id: int, ckey: string, name: string} */
            'game_admin' => $this->whenLoaded('gameAdmin', function () {
                return $this->gameAdmin()->select('id', 'ckey', 'name')->first();
            }),
        ];
    }
}
