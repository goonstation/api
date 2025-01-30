<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameBuildTestMergeCreateRequest extends FormRequest
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
            /** Required without game_admin_ckey */
            'game_admin_id' => 'required_without:game_admin_ckey|exists:game_admins,id',
            /** Required without game_admin_id */
            'game_admin_ckey' => 'required_without:game_admin_id|exists:game_admins,ckey',
            'pr_id' => 'required|integer',
            /** Required without server_ids */
            'server_id' => 'required_without:server_ids|string|exists:game_servers,server_id',
            /** Required without server_id */
            'server_ids' => 'required_without:server_id|array|exists:game_servers,server_id',
            'commit' => 'nullable|string',
        ];
    }
}
