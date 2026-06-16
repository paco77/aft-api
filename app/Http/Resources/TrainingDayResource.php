<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingDayResource extends JsonResource
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
            'monthly_plan_id' => $this->monthly_plan_id,
            'label' => $this->label,
            'day_number' => $this->day_number,
            'muscle_groups' => $this->muscle_groups,
            'target_volumes' => $this->target_volumes,
            'exercises' => PlannedExerciseResource::collection($this->plannedExercises),
        ];
    }
}
