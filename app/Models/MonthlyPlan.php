<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyPlan extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_client_id',
        'month',
        'year',
        'days_per_week',
        'split_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedClient()
    {
        return $this->belongsTo(User::class , 'assigned_client_id');
    }

    public function trainingDays()
    {
        return $this->hasMany(TrainingDay::class);
    }
}
