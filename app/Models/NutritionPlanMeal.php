<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionPlanMeal extends Model
{
    protected $fillable = [
        'nutrition_plan_id',
        'name',
        'time',
    ];

    public function nutritionPlan()
    {
        return $this->belongsTo(NutritionPlan::class);
    }

    public function foods()
    {
        return $this->hasMany(MealFood::class);
    }
}
