<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameBuildStatusResource extends JsonResource
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
            /** @var GameBuildStatusCurrentResource Game builds in process */
            'current' => $this['current'],
            /** @var GameBuildStatusQueuedResource Game builds in queue */
            'queued' => $this['queued'],
        ];
    }
}
