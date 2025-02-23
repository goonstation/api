<?php

namespace App\Http\Requests\Events;

use App\Http\Requests\IndexQueryRequest;

class TicketsIndexRequest extends IndexQueryRequest
{
    protected $redirectRoute = 'tickets.index';

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
            'sort_by' => 'in:id,target,reason,issuer,votes',
            'filters.target' => 'string',
            'filters.reason' => 'string',
            'filters.issuer' => 'string',
        ]);
    }
}
