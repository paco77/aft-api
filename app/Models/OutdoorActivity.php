<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutdoorActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'distance_km',
        'duration_seconds',
        'avg_pace_min_km',
        'coordinates',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'distance_km' => 'float',
        'duration_seconds' => 'integer',
        'avg_pace_min_km' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
