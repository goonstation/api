<?php

namespace App\Http\Requests\Events;

use App\Http\Requests\IndexQueryRequest;
use App\Rules\Range;

class DeathsIndexRequest extends IndexQueryRequest
{
    protected $redirectRoute = 'deaths.index';

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
            'sort_by' => 'in:id,mob_name,mob_job,bruteloss,fireloss,toxloss,oxyloss,gibbed,last_words,votes',
            'filters.mob_name' => 'string',
            'filters.mob_job' => 'string',
            'filters.bruteloss' => new Range,
            'filters.fireloss' => new Range,
            'filters.toxloss' => new Range,
            'filters.oxyloss' => new Range,
            'filters.gibbed' => 'boolean',
            'filters.last_words' => 'string',
        ]);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'filters.mob_name' => 'name filter',
            'filters.mob_job' => 'job filter',
            'filters.bruteloss' => 'brute damage filter',
            'filters.fireloss' => 'fire damage filter',
            'filters.toxloss' => 'toxin damage filter',
            'filters.oxyloss' => 'oxygen damage filter',
        ]);
    }
}
