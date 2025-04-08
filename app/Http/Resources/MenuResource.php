<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $meta = json_decode($this->meta, true);

        if (!isset($meta['title'])) {
            $meta['title'] = __($this->name);
        }

        if (!isset($meta['icon'])) {
            $meta['icon'] = $this->icon;
        }

        if (!isset($meta['order'])) {
            $meta['order'] = $this->order;
        }

        return [
            'id' => $this->id,
            'pid' => $this->parent_id,
            'name' => __($this->name),
            'status' => $this->status,
            'type' => $this->type,
            'icon' => $this->icon,
            'path' => $this->url,
            'component' => $this->component,
            'authCode' => $this->permission, // 权限标识
            'meta' => $meta,
            // 'permission' => $this->permission,
            // 'order' => $this->order,
            // 'active' => $this->active,
            'children' => MenuResource::collection($this->whenLoaded('allChildren')),
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
