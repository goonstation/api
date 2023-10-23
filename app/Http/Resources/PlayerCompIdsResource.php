<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerCompIdsResource extends JsonResource
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
            /** @var PlayerConnectionResource */
            'latest_connection' => $this['latest_connection'],
            /** @var array{comp_id: string, connected: int} */
            'comp_ids' => $this['comp_ids'],
        ];
    }
}
