<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameBuildCreateRequest extends FormRequest
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
            'game_admin_id' => 'required_without:game_admin_ckey|exists:game_admins,id',
            'game_admin_ckey' => 'required_without:game_admin_id|exists:game_admins,ckey',
            'server_id' => 'required|string',
            'map' => 'nullable|string|exists:maps,map_id',
        ];
    }
}
