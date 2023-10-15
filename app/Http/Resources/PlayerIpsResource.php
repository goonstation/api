<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PlayerConnectionResource;

class PlayerIpsResource extends JsonResource
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
            /** @var array{ip: string, connected: int} */
            'ips' => $this['ips'],
        ];
    }
}
