<?php

namespace App\Http\Resources;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $permissions = $this->permissions->pluck('name')->toArray();
        $menu_ids = Menu::whereIn('permission', $permissions)->pluck('id')->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            // 'guard_name' => $this->guard_name,
            // 'created_at' => $this->created_at,
            'createTime' => $this->created_at->format('Y-m-d H:i:s'),
            'remark' => $this->remark,
            // 'updated_at' => $this->updated_at,
            // 'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            // return pemission id
            'permissions' => $menu_ids,
        ];
    }
}
