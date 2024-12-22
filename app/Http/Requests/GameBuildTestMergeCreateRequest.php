<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $serverId = $this->input('server_id');
        $prId = $this->input('pr_id');

        return [
            'game_admin_id' => 'required_without:game_admin_ckey|exists:game_admins,id',
            'game_admin_ckey' => 'required_without:game_admin_id|exists:game_admins,ckey',
            'pr_id' => [
                'required',
                'integer',
                Rule::unique('game_build_test_merges')->where(function ($q) use ($serverId, $prId) {
                    return $q->where('server_id', $serverId)->where('pr_id', $prId);
                }),
            ],
            'server_id' => 'required|string|exists:game_servers,server_id',
            'commit' => 'nullable|string',
        ];
    }
}
