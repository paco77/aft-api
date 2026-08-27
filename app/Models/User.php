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
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            if ($user->logo_path) {
                Storage::disk('public')->delete($user->logo_path);
            }
            if ($user->front_photo) {
                Storage::disk('public')->delete($user->front_photo);
            }
            if ($user->side_photo) {
                Storage::disk('public')->delete($user->side_photo);
            }
            if ($user->back_photo) {
                Storage::disk('public')->delete($user->back_photo);
            }
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
