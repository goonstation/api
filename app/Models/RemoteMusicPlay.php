<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $title
 * @property int $round_id
 * @property int|null $game_admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay whereGameAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RemoteMusicPlay whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class RemoteMusicPlay extends BaseModel
{
    use HasFactory;
}
