<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_items_count' => $this->total(),
            'current_page_items_count' => $this->count(),
            'last_page' => $this->lastPage(),
            'items_per_page' => $this->perPage(),
            'current_page_number' => $this->currentPage(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
            'total_pages' => $this->lastPage(),
            'has_more_records' => !is_null($this->nextPageUrl())
        ];
    }
}
