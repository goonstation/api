<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $password
 * @property string $numbers
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumbersStationPassword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumbersStationPassword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumbersStationPassword query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumbersStationPassword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumbersStationPassword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumbersStationPassword whereNumbers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumbersStationPassword wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumbersStationPassword whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class NumbersStationPassword extends BaseModel
{
    use HasFactory;

    protected $table = 'numbers_station_password';
}
