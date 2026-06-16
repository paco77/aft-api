<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkoutSession;
use App\Models\ExerciseLog;
use App\Models\SetLog;
use App\Models\User;
use App\Http\Resources\WorkoutSessionResource;
use App\Models\TrainingDay;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WorkoutController extends Controller
{
    public function startSession(Request $request)
    {
        $data = $request->validate([
            'training_day_id' => 'required|exists:training_days,id',
            'start_time' => 'date',
        ]);

        $session = WorkoutSession::create([
            'user_id' => $request->user()->id,
            'training_day_id' => $data['training_day_id'],
            'start_time' => $data['start_time'] ?? Carbon::now(),
        ]);

        return new WorkoutSessionResource($session);
    }

    public function logExercise(Request $request, WorkoutSession $session)
    {
        // Safety check: only the session owner can log exercises
        if ($session->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'planned_exercise_id' => 'required|exists:planned_exercises,id',
        ]);

        $log = $session->exerciseLogs()->create([
            'planned_exercise_id' => $data['planned_exercise_id'],
        ]);

        return response()->json([
            'id' => $log->id,
            'workout_session_id' => $log->workout_session_id,
            'planned_exercise_id' => $log->planned_exercise_id,
        ]);
    }

    public function logSet(Request $request, ExerciseLog $log)
    {
        // Safety check: log must belong to a session owned by the user
        if ($log->workoutSession->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'set_number' => 'required|integer',
            'weight' => 'required|numeric',
            'reps' => 'required|integer',
        ]);

        $set = $log->setLogs()->updateOrCreate(
        ['set_number' => $data['set_number']],
        [
            'weight' => $data['weight'],
            'reps' => $data['reps'],
        ]
        );

        return response()->json($set);
    }

    public function finishSession(Request $request, WorkoutSession $session)
    {
        // Safety check
        if ($session->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'end_time' => 'date',
            'comments' => 'string|nullable',
        ]);

        $session->update([
            'end_time' => $data['end_time'] ?? Carbon::now(),
            'comments' => $data['comments'],
        ]);

        return new WorkoutSessionResource($session->load('exerciseLogs.setLogs', 'trainingDay'));
    }

    public function bulkLog(Request $request)
    {
        Log::info('Bulk Log Request:', $request->all());
        $data = $request->validate([
            'training_day_id' => 'required|exists:training_days,id',
            'start_time' => 'date',
            'end_time' => 'date',
            'comments' => 'string|nullable',
            'exercises' => 'required|array',
            'exercises.*.planned_exercise_id' => 'required|exists:planned_exercises,id',
            'exercises.*.set_logs' => 'required|array',
            'exercises.*.set_logs.*.set_number' => 'required|integer',
            'exercises.*.set_logs.*.weight' => 'required|numeric',
            'exercises.*.set_logs.*.reps' => 'required|integer',
        ]);

        $trainingDay = TrainingDay::findOrFail($data['training_day_id']);
        $plan = $trainingDay->monthlyPlan;

        // Authorization: Only the assigned client or their coach can log sessions on this plan
        if ($plan->assigned_client_id !== $request->user()->id) {
            $client = User::find($plan->assigned_client_id);
            if (!$client || $client->coach_id !== $request->user()->id) {
                return response()->json(['message' => 'Solo el cliente asignado o su coach pueden registrar entrenamientos en este plan.'], 403);
            }
        }

        $session = WorkoutSession::create([
            'user_id' => $plan->assigned_client_id,
            'training_day_id' => $data['training_day_id'],
            'start_time' => $data['start_time'] ?? Carbon::now(),
            'end_time' => $data['end_time'] ?? Carbon::now(),
            'comments' => $data['comments'] ?? null,
        ]);

        foreach ($data['exercises'] as $exData) {
            $exLog = $session->exerciseLogs()->create([
                'planned_exercise_id' => $exData['planned_exercise_id'],
            ]);

            foreach ($exData['set_logs'] as $setData) {
                $exLog->setLogs()->create([
                    'set_number' => $setData['set_number'],
                    'weight' => $setData['weight'],
                    'reps' => $setData['reps'],
                ]);
            }
        }

        return new WorkoutSessionResource($session->load('exerciseLogs.setLogs', 'trainingDay'));
    }

    public function history(Request $request)
    {
        $userId = $request->user()->id;

        // Si se solicita el historial de un cliente específico (para coaches)
        if ($request->has('client_id') && $request->input('client_id') != $request->user()->id) {
            $clientId = $request->input('client_id');
            $client = User::find($clientId);

            if (!$client || $client->coach_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $userId = $clientId;
        }

        $sessions = WorkoutSession::where('user_id', $userId)
            ->with(['trainingDay', 'exerciseLogs.setLogs', 'exerciseLogs.plannedExercise.exercise'])
            ->latest()
            ->get();

        return WorkoutSessionResource::collection($sessions);
    }
}
