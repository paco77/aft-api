<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    protected $table = 'food_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function foods()
    {
        return $this->hasMany(Food::class, 'food_category_id');
    }
}

