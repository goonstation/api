<?php

namespace App\Http\Resources;

use App\Models\GameAdmin;
use App\Models\GameServer;
use Illuminate\Http\Resources\Json\JsonResource;

class GameBuildStatusQueuedResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            /** @var GameAdmin */
            'admin' => $this['admin'],
            /** @var GameServer */
            'server' => $this['server'],
            /** @var bool */
            'mapSwitch' => $this['mapSwitch'],
            /** @format date-time */
            'startedAt' => $this['startedAt'],
        ];
    }
}
