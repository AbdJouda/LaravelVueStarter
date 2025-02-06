<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'due_date' => DateResource::make($this->due_date),
            'priority' => $this->priority,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
