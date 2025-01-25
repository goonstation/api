<?php

namespace App\Models;

use App\Models\Events\EventAiLaw;
use App\Models\Events\EventAntag;
use App\Models\Events\EventAntagItemPurchase;
use App\Models\Events\EventAntagObjective;
use App\Models\Events\EventBeeSpawn;
use App\Models\Events\EventDeath;
use App\Models\Events\EventError;
use App\Models\Events\EventFine;
use App\Models\Events\EventGauntletHighScore;
use App\Models\Events\EventLog;
use App\Models\Events\EventStationName;
use App\Models\Events\EventTicket;
use App\Traits\HasOpenGraphData;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string|null $server_id
 * @property string|null $map
 * @property string|null $game_type
 * @property bool $rp_mode
 * @property bool $crashed
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $test_merges
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventAiLaw> $aiLaws
 * @property-read int|null $ai_laws_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventAntagItemPurchase> $antagItemPurchases
 * @property-read int|null $antag_item_purchases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventAntagObjective> $antagObjectives
 * @property-read int|null $antag_objectives_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventAntag> $antags
 * @property-read int|null $antags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventBeeSpawn> $beeSpawns
 * @property-read int|null $bee_spawns_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerConnection> $connections
 * @property-read int|null $connections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventDeath> $deaths
 * @property-read int|null $deaths_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventError> $errors
 * @property-read int|null $errors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventFine> $fines
 * @property-read int|null $fines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventGauntletHighScore> $gauntletHighScores
 * @property-read int|null $gauntlet_high_scores_count
 * @property-read EventStationName|null $latestStationName
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventLog> $logs
 * @property-read int|null $logs_count
 * @property-read \App\Models\Map|null $mapRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerParticipation> $participations
 * @property-read int|null $participations_count
 * @property-read \App\Models\GameServer|null $server
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventStationName> $stationNames
 * @property-read int|null $station_names_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventTicket> $tickets
 * @property-read int|null $tickets_count
 *
 * @method static \Database\Factories\GameRoundFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereCrashed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereGameType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereMap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereRpMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereTestMerges($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameRound whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class GameRound extends BaseModel
{
    use Filterable, HasFactory, HasOpenGraphData;

    protected $casts = [
        'ended_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'server_id',
        'game_type',
        'crashed',
        'ended_at',
        'created_at',
        'updated_at',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(GameServer::class, 'server_id', 'server_id');
    }

    public function stationNames(): HasMany
    {
        return $this->hasMany(EventStationName::class, 'round_id');
    }

    public function aiLaws(): HasMany
    {
        return $this->hasMany(EventAiLaw::class, 'round_id');
    }

    public function beeSpawns(): HasMany
    {
        return $this->hasMany(EventBeeSpawn::class, 'round_id');
    }

    public function deaths(): HasMany
    {
        return $this->hasMany(EventDeath::class, 'round_id');
    }

    public function fines(): HasMany
    {
        return $this->hasMany(EventFine::class, 'round_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(EventTicket::class, 'round_id');
    }

    public function gauntletHighScores(): HasMany
    {
        return $this->hasMany(EventGauntletHighScore::class, 'round_id');
    }

    public function antags(): HasMany
    {
        return $this->hasMany(EventAntag::class, 'round_id');
    }

    public function antagObjectives(): HasMany
    {
        return $this->hasMany(EventAntagObjective::class, 'round_id');
    }

    public function antagItemPurchases(): HasMany
    {
        return $this->hasMany(EventAntagItemPurchase::class, 'round_id');
    }

    public function connections(): HasMany
    {
        return $this->hasMany(PlayerConnection::class, 'round_id');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(PlayerParticipation::class, 'round_id');
    }

    public function mapRecord(): HasOne
    {
        return $this->hasOne(Map::class, 'map_id', 'map');
    }

    // public function antags(): HasMany
    // {
    //     return $this->hasMany(PlayerAntag::class, 'round_id');
    // }

    public function latestStationName(): HasOne
    {
        return $this->hasOne(EventStationName::class, 'round_id')->latest();
    }

    public function logs(): HasMany
    {
        return $this->hasMany(EventLog::class, 'round_id');
    }

    public function errors(): HasMany
    {
        return $this->hasMany(EventError::class, 'round_id');
    }

    public static function getOpenGraphData(int $id)
    {
        return self::with([
            'server',
            'latestStationName',
            'mapRecord',
        ])
            ->where('id', $id)
            ->where('ended_at', '!=', null)
            ->whereRelation('server', 'invisible', '!=', true)
            ->firstOrFail();
    }
}
