<?php

namespace App\Http\Resources;

use App\Models\PollAnswer;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PollAnswer */
class PollAnswerResource extends JsonResource
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
            'poll_option_id' => $this->poll_option_id,
            'poll_id' => $this->option->poll->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
