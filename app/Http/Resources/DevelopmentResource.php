<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DevelopmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalDays = $this->start_date && $this->end_date ?
            \Carbon\Carbon::parse($this->start_date)->diffInDays(\Carbon\Carbon::parse($this->end_date)) : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'person_in_charge' => $this->person_in_charge,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'duration' => $totalDays,
            'amount' => $this->amount,
            'status' => $this->status,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
