<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VpnCheckResource extends JsonResource
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
            'ip' => $this->ip,
            'service' => $this->service,
            'response' => $this->response,
            'error' => $this->error,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            /** @var array{cached: bool, whitelisted: bool} */
            'meta' => $this->meta,
        ];
    }
}
