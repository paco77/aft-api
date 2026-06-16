<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Assuming User model is in App\Models
use App\Models\NutritionPlanMeal; // Assuming NutritionPlanMeal model is in App\Models

class NutritionPlan extends Model
{
    protected $fillable = [
        'coach_id',
        'client_id',
        'name',
        'description',
        'total_calories',
        'total_protein',
        'total_carbs',
        'total_fat',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class , 'coach_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class , 'client_id');
    }

    public function meals()
    {
        return $this->hasMany(NutritionPlanMeal::class);
    }
}
