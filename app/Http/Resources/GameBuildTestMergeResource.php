<?php

namespace App\Http\Resources;

use App\Models\GameBuildTestMerge;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GameBuildTestMerge */
class GameBuildTestMergeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pr_id' => $this->pr_id,
            'server_id' => $this->server_id,
            'added_by' => $this->addedBy,
            'updated_by' => $this->updatedBy,
            'commit' => $this->commit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
