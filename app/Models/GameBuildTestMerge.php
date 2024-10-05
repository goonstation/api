<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameBuildTestMerge extends Model
{
    use HasFactory;

    protected $table = 'game_build_test_merges';

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
    public function addedBy()
    {
        return $this->belongsTo(GameAdmin::class, 'added_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(GameAdmin::class, 'updated_by');
    }
}
