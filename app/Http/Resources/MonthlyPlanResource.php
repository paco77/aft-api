<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $logs = [];
        foreach ($this->trainingDays as $day) {
            $sessions = [];
            foreach ($day->workoutSessions->sortBy('id') as $index => $session) {
                $exercises = [];
                foreach ($session->exerciseLogs as $exLog) {
                    $exercises[] = [
                        'exerciseId' => $exLog->planned_exercise_id,
                        'setLogs' => $exLog->setLogs->map(function ($set) {
                        return [
                        'reps' => $set->reps,
                        'weight' => $set->weight,
                        ];
                    }),
                    ];
                }

                $sessions[] = [
                    'sessionNumber' => $index + 1,
                    'date' => $session->start_time->toIso8601String(),
                    'exercises' => $exercises,
                    'comment' => $session->comments,
                ];
            }

            if (!empty($sessions)) {
                $logs[] = [
                    'dayNumber' => $day->day_number,
                    'sessions' => $sessions,
                ];
            }
        }

        return [
            'id' => $this->id,
            'coach' => new UserResource($this->user),
            'client' => new UserResource($this->assignedClient),
            'assigned_client_id' => $this->assigned_client_id,
            'month' => $this->month,
            'year' => $this->year,
            'days_per_week' => $this->days_per_week,
            'split_type' => $this->split_type,
            'training_days' => TrainingDayResource::collection($this->trainingDays),
            'logs' => $logs,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
