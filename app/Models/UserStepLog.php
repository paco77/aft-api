<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStepLog extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'steps',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
