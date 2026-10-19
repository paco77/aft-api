<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingDay;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GymRoutineController extends Controller
{
    /**
     * List all gym routines for the authenticated gym or all if admin.
     */
    public function index()
    {
        $user = auth()->user();

        $query = TrainingDay::with(['plannedExercises.exercise', 'user'])
            ->whereNotNull('user_id')
            ->whereNull('monthly_plan_id')
            ->latest();

        if ($user->role === 'gimnasio') {
            $query->where('user_id', $user->id);
        }

        $routines = $query->paginate(15);
        $gymUsers = $user->role === 'admin' ? User::where('role', 'gimnasio')->get() : collect([$user]);

        return view('admin.gym_routines.index', compact('routines', 'gymUsers'));
    }

    /**
     * Show form to create a new gym routine.
     */
    public function create()
    {
        $user = auth()->user();
        $gymUsers = $user->role === 'admin' ? User::where('role', 'gimnasio')->get() : collect([$user]);
        $exercises = Exercise::with('muscleGroup')->where('is_active', true)->orderBy('name')->get();
        $muscleGroups = MuscleGroup::all();

        return view('admin.gym_routines.create', compact('gymUsers', 'exercises', 'muscleGroups'));
    }

    /**
     * Store newly created gym routine.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'user_id' => $user->role === 'admin' ? 'required|exists:users,id' : 'nullable',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'comment' => 'nullable|string|max:1000',
            'estimated_minutes' => 'nullable|integer|min:5|max:240',
            'muscle_groups' => 'nullable|array',
            'muscle_groups.*' => 'string',
            'exercises' => 'required|array|min:1',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1|max:20',
            'exercises.*.min_reps' => 'required|integer|min:1|max:100',
            'exercises.*.max_reps' => 'required|integer|min:1|max:100',
            'exercises.*.instruction' => 'nullable|string|max:500',
        ]);

        $gymUserId = ($user->role === 'admin' && !empty($validated['user_id'])) ? $validated['user_id'] : $user->id;

        DB::transaction(function () use ($validated, $gymUserId) {
            $count = TrainingDay::where('user_id', $gymUserId)->whereNull('monthly_plan_id')->count();

            $routine = TrainingDay::create([
                'user_id' => $gymUserId,
                'monthly_plan_id' => null,
                'label' => $validated['label'],
                'description' => $validated['description'] ?? null,
                'comment' => $validated['comment'] ?? null,
                'day_number' => $count + 1,
                'estimated_minutes' => $validated['estimated_minutes'] ?? 60,
                'muscle_groups' => $validated['muscle_groups'] ?? [],
            ]);

            foreach ($validated['exercises'] as $exerciseData) {
                $routine->plannedExercises()->create([
                    'exercise_id' => $exerciseData['exercise_id'],
                    'sets' => $exerciseData['sets'],
                    'min_reps' => $exerciseData['min_reps'],
                    'max_reps' => $exerciseData['max_reps'],
                    'instruction' => $exerciseData['instruction'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.gym-routines.index')->with('success', 'Rutina de gimnasio creada exitosamente.');
    }

    /**
     * Show routine details.
     */
    public function show(TrainingDay $gym_routine)
    {
        $this->authorizeRoutine($gym_routine);
        $gym_routine->load(['plannedExercises.exercise.muscleGroup', 'user']);

        return view('admin.gym_routines.show', ['routine' => $gym_routine]);
    }

    /**
     * Show form to edit gym routine.
     */
    public function edit(TrainingDay $gym_routine)
    {
        $this->authorizeRoutine($gym_routine);
        $gym_routine->load(['plannedExercises.exercise']);

        $user = auth()->user();
        $gymUsers = $user->role === 'admin' ? User::where('role', 'gimnasio')->get() : collect([$user]);
        $exercises = Exercise::with('muscleGroup')->where('is_active', true)->orderBy('name')->get();
        $muscleGroups = MuscleGroup::all();

        return view('admin.gym_routines.edit', ['routine' => $gym_routine, 'gymUsers' => $gymUsers, 'exercises' => $exercises, 'muscleGroups' => $muscleGroups]);
    }

    /**
     * Update gym routine.
     */
    public function update(Request $request, TrainingDay $gym_routine)
    {
        $this->authorizeRoutine($gym_routine);
        $user = auth()->user();

        $validated = $request->validate([
            'user_id' => $user->role === 'admin' ? 'required|exists:users,id' : 'nullable',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'comment' => 'nullable|string|max:1000',
            'estimated_minutes' => 'nullable|integer|min:5|max:240',
            'muscle_groups' => 'nullable|array',
            'muscle_groups.*' => 'string',
            'exercises' => 'required|array|min:1',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1|max:20',
            'exercises.*.min_reps' => 'required|integer|min:1|max:100',
            'exercises.*.max_reps' => 'required|integer|min:1|max:100',
            'exercises.*.instruction' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($gym_routine, $validated, $user) {
            $gym_routine->update([
                'user_id' => ($user->role === 'admin' && !empty($validated['user_id'])) ? $validated['user_id'] : $gym_routine->user_id,
                'label' => $validated['label'],
                'description' => $validated['description'] ?? null,
                'comment' => $validated['comment'] ?? null,
                'estimated_minutes' => $validated['estimated_minutes'] ?? $gym_routine->estimated_minutes,
                'muscle_groups' => $validated['muscle_groups'] ?? [],
            ]);

            // Recreate planned exercises cleanly
            $gym_routine->plannedExercises()->delete();

            foreach ($validated['exercises'] as $exerciseData) {
                $gym_routine->plannedExercises()->create([
                    'exercise_id' => $exerciseData['exercise_id'],
                    'sets' => $exerciseData['sets'],
                    'min_reps' => $exerciseData['min_reps'],
                    'max_reps' => $exerciseData['max_reps'],
                    'instruction' => $exerciseData['instruction'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.gym-routines.index')->with('success', 'Rutina de gimnasio actualizada correctamente.');
    }

    /**
     * Delete gym routine.
     */
    public function destroy(TrainingDay $gym_routine)
    {
        $this->authorizeRoutine($gym_routine);
        $gym_routine->delete();

        return redirect()->route('admin.gym-routines.index')->with('success', 'Rutina eliminada correctamente.');
    }

    /**
     * Show QR Code generation and poster view for gym.
     */
    public function qr(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $gymId = $request->query('gym_id');
            $gym = $gymId ? User::find($gymId) : User::where('role', 'gimnasio')->first();
            if (!$gym) {
                $gym = $user; // fallback
            }
        } else {
            $gym = $user;
        }

        $allGyms = $user->role === 'admin' ? User::where('role', 'gimnasio')->get() : collect([$user]);

        // Construct public URL that will be encoded in the QR code
        $targetIdentifier = $gym->username ?: $gym->id;
        $publicUrl = url("/gimnasio/{$targetIdentifier}");

        $routinesCount = TrainingDay::where('user_id', $gym->id)->whereNull('monthly_plan_id')->count();

        return view('admin.gym_routines.qr', compact('gym', 'publicUrl', 'allGyms', 'routinesCount'));
    }

    /**
     * Check if user is authorized to manage this routine.
     */
    protected function authorizeRoutine(TrainingDay $routine): void
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'gimnasio' && $routine->user_id === $user->id) {
            return;
        }

        abort(403, 'No tienes permiso para administrar esta rutina.');
    }
}
