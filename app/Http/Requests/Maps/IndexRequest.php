<?php

namespace App\Http\Requests\Maps;

use App\Http\Requests\IndexQueryRequest;
use App\Rules\DateRange;

class IndexRequest extends IndexQueryRequest
{
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
            'sort_by' => 'in:id,map_id,name,last_built_at,created_at,updated_at',
            'filters.map_id' => 'string',
            'filters.name' => 'string',
            'filters.last_built_by' => 'string',
            'filters.last_built_at' => new DateRange,
            'filters.created_at' => new DateRange,
            'filters.updated_at' => new DateRange,
        ]);
    }
}
