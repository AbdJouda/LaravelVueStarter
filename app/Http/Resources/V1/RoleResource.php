<?php

namespace App\Http\Resources\V1;

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
        return [
            'attributes' => [
                'id' => $this->getKey(),
                'name' => $this->name,
                'display_name' => $this->display_name,
                'users_count' => $this->whenCounted('users'),
                'permissions_count' => $this->whenCounted('permissions'),
            ],
            'relations' => [
                'permissions' => RoleResource::collection($this->whenLoaded('permissions'))
            ],
        ];
    }
}
