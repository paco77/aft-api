<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'muscle_group_id',
        'equipment',
        'description',
        'slug',
        'primary_muscles',
        'secondary_muscles',
        'benefits',
        'level',
        'is_custom',
        'user_id',
    ];

    protected $casts = [
        'primary_muscles' => 'array',
        'secondary_muscles' => 'array',
        'benefits' => 'array',
        'is_custom' => 'boolean',
    ];

    public function muscleGroup()
    {
        return $this->belongsTo(MuscleGroup::class);
    }

    public function plannedExercises()
    {
        return $this->hasMany(PlannedExercise::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
