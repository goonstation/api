<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int|null $round_id
 * @property string $ip
 * @property string $service
 * @property string|null $response
 * @property string|null $error
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnCheck whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class VpnCheck extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'ip',
        'service',
        'error',
        'response',
    ];
}
