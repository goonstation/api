<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VpnWhitelist extends Model
{
    use HasFactory, Filterable;

    protected $table = 'vpn_whitelist';

    protected $fillable = [
        'ckey',
        'game_admin_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class);
    }
}
