<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexQueryRequest extends FormRequest
{
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
        return [
            /**
             * Filter the results by column and value pairs. (Note: Unsupported in web documentation)
             *
             * @example filters[id]=1
             */
            'filters' => 'array',
            /**
             * What column to sort results by
             *
             * @example id
             */
            'sort_by' => 'string',
            /**
             * true or false
             *
             * @example true
             */
            'descending' => 'string',
            /**
             * How many items to show per page
             *
             * @example 30
             */
            'per_page' => 'int',
            /**
             * What page of results to display
             *
             * @example 1
             */
            'page' => 'int',
        ];
    }
}
