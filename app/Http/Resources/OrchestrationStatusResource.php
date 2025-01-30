<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrchestrationStatusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'status' => $this['status'],
            'health' => $this['health'],
            /** @format date-time */
            'startedAt' => $this['startedAt'],
            /** @var bool */
            'restarting' => $this['restarting'],
        ];
    }
}
