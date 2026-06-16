<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlannedExerciseResource extends JsonResource
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
            'exercise' => new ExerciseResource($this->exercise),
            'sets' => $this->sets,
            'min_reps' => $this->min_reps,
            'max_reps' => $this->max_reps,
            'instruction' => $this->instruction,
        ];
    }
}
