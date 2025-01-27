<?php

namespace App\Http\Resources\V1;

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
            'attributes' => [
                'id' => $this->getKey(),
                'name' => $this->whenHas('name'),
                'email' => $this->whenHas('email'),
                'phone' => $this->whenHas('phone'),
                'is_active' => $this->is_active,
                'profile_photo_url' => $this->whenHas('profile_photo_path', $this->profile_photo_url),
                'permissions_count' => $this->whenCounted('permissions'),
                'has_new_notifications' => $this->whenAppended('hasNewNotifications'),
            ],
            'relations' => [
                'roles' => RoleResource::collection($this->whenLoaded('roles')),
                'permissions' => RoleResource::collection($this->whenLoaded('permissions')),
            ],
        ];
    }
}
