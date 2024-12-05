<?php

namespace App\Http\Resources;

use App\Models\PollOption;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PollOption */
class PollOptionResource extends JsonResource
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
            'poll_id' => $this->poll_id,
            'option' => $this->option,
            /** @var int */
            'position' => $this->position,
            /** @var int */
            'answers_count' => $this->answers_count,
            /** @var array<int> */
            'answers_player_ids' => $this->answers,
        ];
    }
}
