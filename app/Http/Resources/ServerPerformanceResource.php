<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServerPerformanceMetricsResource extends JsonResource
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
            'time' => [
                /** @var float Uptime in number of seconds */
                'uptime' => $this['time']['uptime']
            ],
            'mem' => [
                /** @var int Available memory in bytes  */
                'available' => $this['mem']['available'],
                /** @var int Total memory in bytes  */
                'total' => $this['mem']['total'],
            ],
            'currentLoad' => [
                /** @var float Average load in % */
                'avgLoad' => $this['currentLoad']['avgLoad'],
                /** @var float CPU load in % */
                'currentLoad' => $this['currentLoad']['currentLoad'],
            ],
            'currentLoad' => [
                /** @var float Average load in % */
                'avgLoad' => $this['currentLoad']['avgLoad'],
                /** @var float CPU load in % */
                'currentLoad' => $this['currentLoad']['currentLoad'],
            ],
            'fsSize' => [
                /** @var int Total size in bytes */
                'size' => $this['fsSize'][0]['size'],
                /** @var int Used in bytes */
                'used' => $this['fsSize'][0]['used'],
                /** @var int Available size in bytes */
                'available' => $this['fsSize'][0]['available'],
                /** @var float Use in % */
                'use' => $this['fsSize'][0]['use'],
            ],
        ];
    }
}

class ServerPerformanceResource extends JsonResource
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
            /** @var string Name of the server */
            'name' => $this['name'],
            /** @var ServerPerformanceMetricsResource|null Metrics of the server. Null if server was unreachable. */
            'metrics' => $this['metrics'],
        ];
    }
}
