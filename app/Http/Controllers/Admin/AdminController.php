<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exercise;
use App\Models\MonthlyPlan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->role === 'coach') {
            $stats = [
                'users' => $user->clients()->count(),
                'exercises' => Exercise::count(),
                'plans' => $user->monthlyPlans()->count(),
            ];
        } elseif ($user && $user->role === 'gimnasio') {
            $stats = [
                'gym_routines' => $user->gymRoutines()->count(),
                'exercises' => Exercise::count(),
                'sessions' => \App\Models\WorkoutSession::whereIn('training_day_id', $user->gymRoutines()->pluck('id'))->count(),
            ];
        } else {
            $stats = [
                'users' => User::count(),
                'exercises' => Exercise::count(),
                'plans' => MonthlyPlan::count(),
                'gym_routines' => \App\Models\TrainingDay::whereNotNull('user_id')->count(),
            ];
        }

        return view('admin.dashboard', compact('stats'));
    }
}
