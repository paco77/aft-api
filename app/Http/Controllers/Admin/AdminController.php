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
        } else {
            $stats = [
                'users' => User::count(),
                'exercises' => Exercise::count(),
                'plans' => MonthlyPlan::count(),
            ];
        }

        return view('admin.dashboard', compact('stats'));
    }
}
