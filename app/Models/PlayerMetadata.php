<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int|null $player_id
 * @property string $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player|null $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMetadata whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayerMetadata extends BaseModel
{
    use Filterable, HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
