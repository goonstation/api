<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $phrase
 * @property int $round_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DectalkPhrase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DectalkPhrase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DectalkPhrase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DectalkPhrase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DectalkPhrase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DectalkPhrase wherePhrase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DectalkPhrase whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DectalkPhrase whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class DectalkPhrase extends BaseModel
{
    use HasFactory;
}
