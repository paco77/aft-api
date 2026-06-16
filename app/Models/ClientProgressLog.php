<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProgressLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'coach_id',
        'weight',
        'measurements',
        'front_photo_path',
        'side_photo_path',
        'back_photo_path',
        'comments',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'date',
        'measurements' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
