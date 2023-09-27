<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GameAdminResource;
use App\Http\Resources\PollOptionResource;

class PollResource extends JsonResource
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
            'game_admin_id' => $this->game_admin_id,
            /** @var GameAdminResource */
            'game_admin' => $this->gameAdmin,
            'question' => $this->question,
            'options' => PollOptionResource::collection($this->options),
            /** @var array */
            'servers' => json_decode($this->servers),
            /** @var int */
            'total_answers' => $this->total_answers,
            /** @var int|null */
            'winning_option_id' => $this->winning_option_id,
            'multiple_choice' => $this->multiple_choice,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
