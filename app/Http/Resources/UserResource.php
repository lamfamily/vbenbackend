<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'realName' => $this->name,
            'email' => $this->email,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            // 现在只有一个role，要返回给前端
            'role' => RoleResource::collection($this->whenLoaded('roles'))[0]['id'],
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'status' => $this->status,
            'createTime' => $this->created_at->format('Y-m-d H:i:s'),
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
