<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerSearchResource extends JsonResource
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
            /** @var int */
            'id' => $this['id'],
            'ip' => $this['ip'],
            'comp_id' => $this['comp_id'],
            /** @var int */
            'player_id' => $this['player_id'],
            'ckey' => $this['ckey'],
            'created_at' => $this['created_at'],
        ];
    }
}
