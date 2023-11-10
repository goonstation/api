<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BanDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ckey',
        'comp_id',
        'ip',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ban()
    {
        return $this->belongsTo(Ban::class);
    }
}
