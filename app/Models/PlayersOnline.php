<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $server_id
 * @property int|null $online
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayersOnline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayersOnline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayersOnline query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayersOnline whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayersOnline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayersOnline whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayersOnline whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayersOnline whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayersOnline extends Model
{
    use HasFactory;

    protected $table = 'players_online';
}
