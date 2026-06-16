<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sets = SetLogResource::collection($this->setLogs);
        return [
            'id' => $this->id,
            'planned_exercise' => new PlannedExerciseResource($this->plannedExercise),
            'setLogs' => $sets,
            'set_logs' => $sets,
            'sets' => $sets, // Keep for compat
        ];
    }
}
