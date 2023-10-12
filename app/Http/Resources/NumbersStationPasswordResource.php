<?php

namespace App\Http\Resources;

use App\Models\Ban;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ban
 */
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
