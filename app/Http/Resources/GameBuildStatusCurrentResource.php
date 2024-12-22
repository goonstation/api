<?php

namespace App\Http\Resources;

use App\Models\GameAdmin;
use App\Models\GameServer;
use Illuminate\Http\Resources\Json\JsonResource;

class GameBuildStatusCurrentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            /** @var GameAdmin */
            'admin' => $this['admin'],
            /** @var GameServer */
            'server' => $this['server'],
        ];
    }
}
