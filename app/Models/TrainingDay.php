<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingDay extends Model
{
    protected $fillable = [
        'monthly_plan_id',
        'label',
        'day_number',
        'muscle_groups',
        'target_volumes',
    ];

    protected $casts = [
        'muscle_groups' => 'array',
        'target_volumes' => 'array',
    ];

    public function monthlyPlan()
    {
        return $this->belongsTo(MonthlyPlan::class);
    }

    public function plannedExercises()
    {
        return $this->hasMany(PlannedExercise::class);
    }

    public function workoutSessions()
    {
        return $this->hasMany(WorkoutSession::class);
    }
}
