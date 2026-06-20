<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NutritionPlanMeal;

class MealFood extends Model
{
    protected $table = 'meal_foods';

    protected $fillable = [
        'nutrition_plan_meal_id',
        'fatsecret_food_id',
        'name',
        'serving_size',
        'serving_unit',
        'calories',
        'protein',
        'carbs',
        'fat',
    ];

    public function meal()
    {
        return $this->belongsTo(NutritionPlanMeal::class , 'nutrition_plan_meal_id');
    }
}
