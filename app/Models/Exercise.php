<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'muscle_group_id',
        'equipment',
        'description',
        'slug',
        'primary_muscles',
        'secondary_muscles',
        'benefits',
        'level',
        'is_custom',
        'user_id',
        'is_active',
        'video_url',
    ];

    protected $casts = [
        'primary_muscles' => 'array',
        'secondary_muscles' => 'array',
        'benefits' => 'array',
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function muscleGroup()
    {
        return $this->belongsTo(MuscleGroup::class);
    }

    public function plannedExercises()
    {
        return $this->hasMany(PlannedExercise::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        $url = trim($this->video_url);

        // Si es un enlace de YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
        }

        // Si ya es una URL completa
        if (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        // Si es ruta relativa local de storage
        if (str_starts_with($url, '/storage/')) {
            return asset(ltrim($url, '/'));
        }

        return asset('storage/' . ltrim($url, '/'));
    }

    public function getIsVideoAttribute(): bool
    {
        if (!$this->video_url) {
            return false;
        }

        return \Illuminate\Support\Str::endsWith(strtolower($this->video_url), ['.mp4', '.webm', '.mov', '.ogg', '.qt']);
    }

    public function getIsYoutubeAttribute(): bool
    {
        if (!$this->video_url) {
            return false;
        }

        return (bool) preg_match('/(?:youtube\.com|youtu\.be)/i', $this->video_url);
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $this->video_url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
