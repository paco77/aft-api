<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected static function booted()
    {
        static::deleting(function ($user) {
            // Eliminar todos los archivos e imágenes del cliente en S3 / Storage
            Storage::deleteDirectory("clients/{$user->id}");
            
            // Eliminar planes asignados al cliente
            \App\Models\NutritionPlan::where('client_id', $user->id)->delete();
            \App\Models\MonthlyPlan::where('assigned_client_id', $user->id)->delete();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'coach_id',
        'weight',
        'height',
        'age',
        'training_time',
        'objectives',
        'training_info',
        'experience_years',
        'profile_photo_path',
        'front_photo',
        'side_photo',
        'back_photo',
        'is_active',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class , 'coach_id');
    }

    public function clients()
    {
        return $this->hasMany(User::class , 'coach_id');
    }

    public function progressLogs()
    {
        return $this->hasMany(ClientProgressLog::class, 'client_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected $appends = [
        'profile_photo_url',
        'front_photo_url',
        'side_photo_url',
        'back_photo_url',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ? \Illuminate\Support\Facades\Storage::disk('s3')->url($this->profile_photo_path) : null;
    }

    public function getFrontPhotoUrlAttribute()
    {
        return $this->front_photo ? \Illuminate\Support\Facades\Storage::disk('s3')->url($this->front_photo) : null;
    }

    public function getSidePhotoUrlAttribute()
    {
        return $this->side_photo ? \Illuminate\Support\Facades\Storage::disk('s3')->url($this->side_photo) : null;
    }

    public function getBackPhotoUrlAttribute()
    {
        return $this->back_photo ? \Illuminate\Support\Facades\Storage::disk('s3')->url($this->back_photo) : null;
    }

    public function monthlyPlans()
    {
        return $this->hasMany(MonthlyPlan::class);
    }

    public function assignedPlans()
    {
        return $this->hasMany(MonthlyPlan::class , 'assigned_client_id');
    }

    public function workoutSessions()
    {
        return $this->hasMany(WorkoutSession::class);
    }
}
