<?php

namespace App\Models;

use App\Models\Events\EventDeath;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'id',
        'ckey',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connections()
    {
        return $this->hasMany(PlayerConnection::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function latestConnection()
    {
        return $this->hasOne(PlayerConnection::class, 'player_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function firstConnection()
    {
        return $this->hasOne(PlayerConnection::class, 'player_id')->oldest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations()
    {
        return $this->hasMany(PlayerParticipation::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playtime()
    {
        return $this->hasMany(PlayerPlaytime::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deaths()
    {
        return $this->hasMany(EventDeath::class, 'player_id');
    }
}
