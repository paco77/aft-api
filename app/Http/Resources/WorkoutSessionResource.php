<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutSessionResource extends JsonResource
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
            'training_day' => new TrainingDayResource($this->trainingDay),
            'start_time' => $this->start_time->toIso8601String(),
            'end_time' => $this->end_time ? $this->end_time->toIso8601String() : null,
            'comments' => $this->comments,
            'exercise_logs' => ExerciseLogResource::collection($this->exerciseLogs),
        ];
    }
}
