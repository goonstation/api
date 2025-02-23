<?php

namespace App\Http\Requests\Events;

use App\Http\Requests\IndexQueryRequest;

class AntagsIndexRequest extends IndexQueryRequest
{
    protected $redirectRoute = 'antags.index';

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
            'sort_by' => 'in:id,mob_name,mob_job,traitor_type,success',
            'filters.mob_name' => 'string',
            'filters.mob_job' => 'string',
            'filters.traitor_type' => 'string',
            'filters.success' => 'boolean',
        ]);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'filters.mob_name' => 'name filter',
            'filters.mob_job' => 'job filter',
            'filters.traitor_type' => 'antagonist type filter',
        ]);
    }
}
