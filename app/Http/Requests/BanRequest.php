<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BanRequest extends FormRequest
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
            'game_admin_ckey' => 'required|exists:game_admins,ckey',
            'round_id' => 'nullable|integer',
            'server_id' => 'nullable|string',
            'ckey' => 'required_without_all:comp_id,ip',
            'comp_id' => 'required_without_all:ckey,ip',
            'ip' => 'required_without_all:ckey,comp_id|ip',
            'reason' => 'required',
            'duration' => 'nullable|integer',
        ];
    }
}
