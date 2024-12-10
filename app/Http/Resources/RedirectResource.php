<?php

namespace App\Http\Resources;

use App\Models\Redirect;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Redirect */
class RedirectResource extends JsonResource
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
            'from' => $this->from,
            'to' => $this->to,
            'visits' => $this->visits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
