<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'food_category_id',
        'subcategory',
        'name',
        'state',
        'portion_type',
        'portion_detail',
        'base_quantity',
        'base_unit',
        'gram_weight',
        'calories',
        'protein',
        'carbs',
        'fat',
    ];

    protected $casts = [
        'base_quantity' => 'float',
        'gram_weight' => 'float',
        'calories' => 'float',
        'protein' => 'float',
        'carbs' => 'float',
        'fat' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id');
    }
}
