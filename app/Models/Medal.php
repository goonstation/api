<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property bool $hidden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $uuid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerMedal> $earned
 * @property-read int|null $earned_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medal whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Medal extends BaseModel
{
    use Filterable, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'hidden',
    ];

    protected $hidden = [
        'id',
    ];

    // public function players()
    // {
    //     return $this->hasManyThrough(Player::class, PlayerMedal::class);
    // }

    public function earned(): HasMany
    {
        return $this->hasMany(PlayerMedal::class);
    }
}
