<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'muscle_group_id' => $this->muscle_group_id,
            'muscle_group' => new MuscleGroupResource($this->whenLoaded('muscleGroup')),
            'equipment' => $this->equipment,
            'description' => $this->description,
            'primary_muscles' => $this->primary_muscles,
            'secondary_muscles' => $this->secondary_muscles,
            'benefits' => $this->benefits,
            'level' => $this->level,
            'is_custom' => (bool)$this->is_custom,
        ];
    }
}
