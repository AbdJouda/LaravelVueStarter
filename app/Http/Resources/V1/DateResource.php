<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Carbon $this */
        return [
            'day' => $this->isoFormat('dddd'),
            'month' => $this->isoFormat('MMMM'),
            'year' => $this->isoFormat('YYYY'),
            'uk_format' => $this->isoFormat('DD/MM/Y'),
            'uk_string' => $this->isoFormat('D MMM YYYY'),
            'iso_8601_format' => $this->format('Y-m-d'),
            'unix_timestamp' => $this->timestamp,
        ];
    }
}
