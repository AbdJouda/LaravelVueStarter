<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeResource extends JsonResource
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
            'hour_minute' => $this->isoFormat('HH:mm'),
            'hour_minute_second' => $this->isoFormat('HH:mm:ss'),
            '12_hour_format' => $this->isoFormat('h:mm A'),
            '24_hour_format' => $this->isoFormat('HH:mm'),
            'unix_timestamp' => $this->timestamp,
        ];

    }
}
