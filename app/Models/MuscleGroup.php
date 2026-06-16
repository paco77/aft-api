<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuscleGroup extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
