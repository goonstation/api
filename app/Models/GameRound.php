<?php

namespace App\Models;

use App\Models\Events\EventAiLaw;
use App\Models\Events\EventAntag;
use App\Models\Events\EventAntagItemPurchase;
use App\Models\Events\EventAntagObjective;
use App\Models\Events\EventBeeSpawn;
use App\Models\Events\EventDeath;
use App\Models\Events\EventFine;
use App\Models\Events\EventGauntletHighScore;
use App\Models\Events\EventStationName;
use App\Models\Events\EventTicket;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRound extends Model
{
    use Filterable, HasFactory;

    protected $dates = ['ended_at'];

    protected $fillable = [
        'id',
        'server_id',
        'game_type',
        'crashed',
        'ended_at',
        'created_at',
        'updated_at',
    ];

    public function server()
    {
        return $this->belongsTo(GameServer::class, 'server_id', 'server_id');
    }

    public function stationNames()
    {
        return $this->hasMany(EventStationName::class, 'round_id');
    }

    public function aiLaws()
    {
        return $this->hasMany(EventAiLaw::class, 'round_id');
    }

    public function beeSpawns()
    {
        return $this->hasMany(EventBeeSpawn::class, 'round_id');
    }

    public function deaths()
    {
        return $this->hasMany(EventDeath::class, 'round_id');
    }

    public function fines()
    {
        return $this->hasMany(EventFine::class, 'round_id');
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'round_id');
    }

    public function gauntletHighScores()
    {
        return $this->hasMany(EventGauntletHighScore::class, 'round_id');
    }

    public function antags()
    {
        return $this->hasMany(EventAntag::class, 'round_id');
    }

    public function antagObjectives()
    {
        return $this->hasMany(EventAntagObjective::class, 'round_id');
    }

    public function antagItemPurchases()
    {
        return $this->hasMany(EventAntagItemPurchase::class, 'round_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connections()
    {
        return $this->hasMany(PlayerConnection::class, 'round_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations()
    {
        return $this->hasMany(PlayerParticipation::class, 'round_id');
    }

    public function mapRecord()
    {
        return $this->hasOne(Map::class, 'map_id', 'map');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function antags()
    // {
    //     return $this->hasMany(PlayerAntag::class, 'round_id');
    // }

    public function latestStationName()
    {
        return $this->hasOne(EventStationName::class, 'round_id')->latest();
    }
}
