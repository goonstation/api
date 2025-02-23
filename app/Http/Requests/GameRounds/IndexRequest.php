<?php

namespace App\Http\Requests\GameRounds;

use App\Http\Requests\IndexQueryRequest;
use App\Rules\DateRange;

class IndexRequest extends IndexQueryRequest
{
    protected $redirectRoute = 'rounds.index';

    protected $errorBag = 'table';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'sort_by' => 'in:id,server_id,map,game_type,rp_mode,crashed,created_at,ended_at',
            'filters.id' => 'integer',
            'filters.server' => 'string',
            'filters.station_name' => 'string',
            'filters.map' => 'string',
            'filters.game_type' => 'string',
            'filters.rp_mode' => 'boolean',
            'filters.crashed' => 'boolean',
            'filters.ended_at' => new DateRange,
            'filters.created_at' => new DateRange,
            'filters.updated_at' => new DateRange,
        ]);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'filters.created_at' => 'started at filter',
        ]);
    }
}
