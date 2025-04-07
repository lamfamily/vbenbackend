<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pid' => $this->parent_id,
            'name' => $this->name,
            'status' => $this->status,
            'order' => $this->order,
            'remark' => $this->remark,
            'createTime' => $this->created_at->format('Y-m-d H:i:s'),
            'children' => DeptResource::collection($this->whenLoaded('allChildren')),
        ];
    }
}
