<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventAiLawResource extends JsonResource
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
            'player_id' => $this->player_id,
            'ai_name' => $this->ai_name,
            'law_number' => $this->law_number,
            'law_text' => $this->law_text,
            'uploader_name' => $this->uploader_name,
            'uploader_job' => $this->uploader_job,
            'uploader_ckey' => $this->uploader_ckey,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
