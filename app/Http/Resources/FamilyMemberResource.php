<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyMemberResource extends JsonResource
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
            'head_of_family' => new HeadOfFamilyResource($this->whenLoaded('headOfFamily')),
            'user' => new UserResource($this->user),
            'profile_picture' => $this->profile_picture,
            'identify_number' => $this->identify_number,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'age' => $this->birth_date ? \Carbon\Carbon::parse($this->birth_date)->age : null,
            'phone_number' => $this->phone_number,
            'occupation' => $this->occupation,
            'marital_status' => $this->marital_status,
            'relation' => $this->relation,
        ];
    }
}
