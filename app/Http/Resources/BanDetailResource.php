<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BanDetailResource extends JsonResource
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
            'ban_id' => $this->ban_id,
            'ckey' => $this->ckey,
            'comp_id' => $this->comp_id,
            'ip' => $this->ip,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            /** @var BanDetailResource */
            'original_ban_detail' => $this->when(!is_null($this->originalBanDetail), $this->originalBanDetail)
        ];
    }
}
