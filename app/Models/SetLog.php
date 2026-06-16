<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetLog extends Model
{
    protected $fillable = [
        'exercise_log_id',
        'set_number',
        'weight',
        'reps',
    ];

    public function exerciseLog()
    {
        return $this->belongsTo(ExerciseLog::class);
    }
}
