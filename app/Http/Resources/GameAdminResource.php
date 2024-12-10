<?php

namespace App\Http\Resources;

use App\Models\GameAdmin;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GameAdmin */
class GameAdminResource extends JsonResource
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
            'name' => $this->name,
            /** @var GameAdminRankResource */
            'rank' => $this->rank,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
