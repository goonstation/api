<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameBuild extends Model
{
    use HasFactory;

    protected $casts = [
        'ended_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameServer()
    {
        return $this->belongsTo(GameServer::class, 'server_id', 'server_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function startedBy()
    {
        return $this->belongsTo(GameAdmin::class, 'started_by');
    }
}
