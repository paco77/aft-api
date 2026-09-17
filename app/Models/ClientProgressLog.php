<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProgressLog extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::deleting(function ($log) {
            \Illuminate\Support\Facades\Storage::deleteDirectory("clients/{$log->client_id}/progress/{$log->id}");
        });
    }

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

    protected $appends = [
        'front_photo_url',
        'side_photo_url',
        'back_photo_url',
    ];

    public function getFrontPhotoUrlAttribute()
    {
        return $this->front_photo_path ? \Illuminate\Support\Facades\Storage::disk('s3')->url($this->front_photo_path) : null;
    }

    public function getSidePhotoUrlAttribute()
    {
        return $this->side_photo_path ? \Illuminate\Support\Facades\Storage::disk('s3')->url($this->side_photo_path) : null;
    }

    public function getBackPhotoUrlAttribute()
    {
        return $this->back_photo_path ? \Illuminate\Support\Facades\Storage::disk('s3')->url($this->back_photo_path) : null;
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
