<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkoutSession;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function index(User $user)
    {
        // Validation: coach can only see their own clients
        if (auth()->user()->role === 'coach') {
            if ($user->coach_id !== auth()->id()) {
                abort(403, 'No tienes permiso para ver los entrenamientos de este usuario.');
            }
        }

        $sessions = WorkoutSession::where('user_id', $user->id)
            ->with(['trainingDay.monthlyPlan'])
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('admin.workouts.index', compact('user', 'sessions'));
    }

    public function show(WorkoutSession $session)
    {
        $user = $session->user;

        // Validation: coach can only see their own clients' sessions
        if (auth()->user()->role === 'coach') {
            if ($user->coach_id !== auth()->id()) {
                abort(403, 'No tienes permiso para ver este entrenamiento.');
            }
        }

        $session->load(['trainingDay.monthlyPlan', 'exerciseLogs.plannedExercise.exercise', 'exerciseLogs.setLogs']);

        return view('admin.workouts.show', compact('session', 'user'));
    }
}
