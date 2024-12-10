<?php

namespace App\Http\Resources;

use App\Models\PlayerMetadata;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PlayerMetadata */
class PlayerMetadataResource extends JsonResource
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
            'player_id' => $this->player_id,
            /** @var array{id: int, ckey: string} */
            'player' => $this->whenLoaded('player', function () {
                return $this->player()->select('id', 'ckey')->first();
            }),
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
