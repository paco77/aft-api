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
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'coach_id' => $this->coach_id,
            'weight' => $this->weight,
            'height' => $this->height,
            'age' => $this->age,
            'training_time' => $this->training_time,
            'objectives' => $this->objectives,
            'training_info' => $this->training_info,
            'experience_years' => $this->experience_years,
            'profile_photo_url' => $this->profile_photo_path ? asset('storage/' . $this->profile_photo_path) : null,
            'front_photo_url' => $this->front_photo ? asset('storage/' . $this->front_photo) : null,
            'side_photo_url' => $this->side_photo ? asset('storage/' . $this->side_photo) : null,
            'back_photo_url' => $this->back_photo ? asset('storage/' . $this->back_photo) : null,
            'coach' => new UserResource($this->whenLoaded('coach')),
        ];
    }
}
