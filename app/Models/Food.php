<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'fatsecret_id',
        'name',
        'calories',
        'fat',
        'carbs',
        'protein',
        'serving_description',
    ];
}
