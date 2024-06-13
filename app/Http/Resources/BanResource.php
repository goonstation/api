<?php

namespace App\Http\Resources;

use App\Models\Ban;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ban
 */
class BanResource extends JsonResource
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
            'reason' => $this->reason,
            /** @var bool */
            'active' => $this->active,
            /** @var int */
            'duration' => $this->duration,
            'duration_human' => $this->duration_human,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            /** @var array{id: int, ckey: string, name: string} */
            'game_admin' => $this->whenLoaded('gameAdmin', function () {
                return $this->gameAdmin()->select('id', 'ckey', 'name')->first();
            }),
            /** @var GameRoundResource */
            'game_round' => $this->whenLoaded('gameRound'),
            /** @var array{id: int, ban_id: int, ckey: string, comp_id: string, ip: string} */
            'original_ban_detail' => $this->whenLoaded('originalBanDetail', function () {
                return $this->originalBanDetail()->select('id', 'ban_id', 'ckey', 'comp_id', 'ip')->first();
            }),
            /** @var array<BanDetailResource> */
            'details' => $this->whenLoaded('details'),
            'requires_appeal' => $this->requires_appeal,
        ];
    }
}
