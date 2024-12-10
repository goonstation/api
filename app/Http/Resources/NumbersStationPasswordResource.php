<?php

namespace App\Http\Resources;

use App\Models\NumbersStationPassword;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin NumbersStationPassword */
class NumbersStationPasswordResource extends JsonResource
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
            'numbers' => $this->numbers,
        ];
    }
}
