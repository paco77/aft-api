<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseLog extends Model
{
    protected $fillable = [
        'workout_session_id',
        'planned_exercise_id',
    ];

    public function workoutSession()
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    public function plannedExercise()
    {
        return $this->belongsTo(PlannedExercise::class);
    }

    public function setLogs()
    {
        return $this->hasMany(SetLog::class);
    }
}
