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
        'comment',
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

    protected static function booted()
    {
        static::deleting(function ($day) {
            $day->plannedExercises()->each(function ($exercise) {
                $exercise->delete();
            });
            $day->workoutSessions()->each(function ($session) {
                $session->delete();
            });
        });
    }
}
