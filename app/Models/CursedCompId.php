<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $comp_id
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CursedCompId newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CursedCompId newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CursedCompId query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CursedCompId whereCompId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CursedCompId whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CursedCompId whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CursedCompId whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CursedCompId whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CursedCompId extends BaseModel
{
    use HasFactory;

    protected $table = 'cursed_comp_ids';
}
