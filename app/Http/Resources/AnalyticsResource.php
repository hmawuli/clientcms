<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalyticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'page_id'        => $this->page_id,
            'views'          => $this->views,
            'unique_visitors'=> $this->unique_visitors,
            'avg_time_spent' => $this->avg_time_spent,
            'bounce_rate'    => $this->bounce_rate,
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
        ];
    }
}
