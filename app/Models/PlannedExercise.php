<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannedExercise extends Model
{
    protected $fillable = [
        'training_day_id',
        'exercise_id',
        'client_id',
        'coach_id',
        'sets',
        'min_reps',
        'max_reps',
        'instruction',
    ];

    public function trainingDay()
    {
        return $this->belongsTo(TrainingDay::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function exerciseLogs()
    {
        return $this->hasMany(ExerciseLog::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class , 'client_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class , 'coach_id');
    }
}
