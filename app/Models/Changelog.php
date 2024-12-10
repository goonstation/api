<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $entries
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Changelog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Changelog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Changelog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Changelog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Changelog whereEntries($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Changelog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Changelog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Changelog extends Model
{
    use HasFactory;

    protected $table = 'changelog';

    protected $fillable = ['entries'];
}
