<?php

namespace App\Http\Resources;

use App\Models\Events\EventTicket;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin EventTicket */
class EventTicketResource extends JsonResource
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
            'target' => $this->target,
            'reason' => $this->reason,
            'issuer' => $this->issuer,
            'issuer_job' => $this->issuer_job,
            'issuer_ckey' => $this->issuer_ckey,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
