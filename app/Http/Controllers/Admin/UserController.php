<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClientProgressLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\ImageUploadTrait;

class UserController extends Controller
{
    use ImageUploadTrait;
    public function index()
    {
        $query = User::with('coach')->latest();
        if (auth()->user()->role === 'coach') {
            $query->where('role', 'client')->where('coach_id', auth()->id());
        }
        $users = $query->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $coaches = User::where('role', 'coach')->get();
        return view('admin.users.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'training_info' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:20480',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'training_time' => 'nullable|string',
            'objectives' => 'nullable|string',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
        ];

        if (auth()->user()->isSuperAdmin()) {
            $rules['role'] = 'required|string|in:coach,client,usuario,admin,independiente,gimnasio';
            $rules['coach_id'] = 'nullable|exists:users,id';
            $rules['is_active'] = 'nullable|boolean';
        }

        $request->validate($rules);

        if (auth()->user()->isSuperAdmin()) {
            $role = $request->role;
            // Solo si el rol es client y se seleccionó un coach se asigna, de lo contrario null (desligado)
            $coach_id = ($role === 'client' && $request->filled('coach_id')) ? $request->coach_id : null;
            $is_active = $request->boolean('is_active', true);
        } else {
            $role = 'client';
            $coach_id = auth()->id();
            $is_active = true;
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'coach_id' => $coach_id,
            'is_active' => $is_active,
            'training_info' => $request->training_info,
            'weight' => $request->weight,
            'height' => $request->height,
            'age' => $request->age,
            'phone' => $request->phone,
            'training_time' => $request->training_time,
            'objectives' => $request->objectives,
        ]);

        $updates = [];
        if ($request->hasFile('profile_photo')) {
            $updates['profile_photo_path'] = $this->processAndStoreImage($request->file('profile_photo'), "clients/{$user->id}/profile", 'profile');
        }
        if ($request->hasFile('front_photo')) {
            $updates['front_photo'] = $this->processAndStoreImage($request->file('front_photo'), "clients/{$user->id}/profile", 'front');
        }
        if ($request->hasFile('side_photo')) {
            $updates['side_photo'] = $this->processAndStoreImage($request->file('side_photo'), "clients/{$user->id}/profile", 'side');
        }
        if ($request->hasFile('back_photo')) {
            $updates['back_photo'] = $this->processAndStoreImage($request->file('back_photo'), "clients/{$user->id}/profile", 'back');
        }

        if (!empty($updates)) {
            $user->update($updates);
        }

        // Crear registro de progreso inicial si hay datos
        if (
            $request->filled('weight') || 
            $request->hasFile('front_photo') || 
            $request->hasFile('side_photo') || 
            $request->hasFile('back_photo') ||
            $request->has('metrics')
        ) {
            
            $measurements = null;
            if ($request->has('metrics') && is_array($request->metrics) && isset($request->metrics['keys']) && isset($request->metrics['values'])) {
                $measurements = [];
                foreach ($request->metrics['keys'] as $index => $key) {
                    $value = $request->metrics['values'][$index] ?? null;
                    if (!empty($key) && $value !== null && $value !== '') {
                        $measurements[$key] = $value;
                    }
                }
                if (empty($measurements)) {
                    $measurements = null;
                }
            }

            $logData = [
                'client_id' => $user->id,
                'coach_id' => $coach_id ?? ($role === User::ROLE_INDEPENDENT ? $user->id : null),
                'weight' => $request->weight,
                'measurements' => $measurements,
                'comments' => 'Registro inicial',
                'recorded_at' => now(),
            ];

            $progressLog = ClientProgressLog::create($logData);

            $logUpdates = [];
            if ($request->hasFile('front_photo')) {
                $logUpdates['front_photo_path'] = $this->processAndStoreImage($request->file('front_photo'), "clients/{$user->id}/progress/{$progressLog->id}", 'front');
            }
            if ($request->hasFile('side_photo')) {
                $logUpdates['side_photo_path'] = $this->processAndStoreImage($request->file('side_photo'), "clients/{$user->id}/progress/{$progressLog->id}", 'side');
            }
            if ($request->hasFile('back_photo')) {
                $logUpdates['back_photo_path'] = $this->processAndStoreImage($request->file('back_photo'), "clients/{$user->id}/progress/{$progressLog->id}", 'back');
            }

            if (!empty($logUpdates)) {
                $progressLog->update($logUpdates);
            }
        }

        $redirect = redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');

        if ($request->boolean('notify_whatsapp') && !empty($user->phone)) {
            $message = urlencode("Hola {$user->name}, tu cuenta ha sido creada. Tu usuario es: {$user->username} y tu contraseña es: {$request->password}. Puedes iniciar sesión en la app.");
            $phone = preg_replace('/[^0-9]/', '', $user->phone);
            $redirect->with('whatsapp_url', "https://wa.me/{$phone}?text={$message}");
        }

        return $redirect;
    }

    public function show(User $user)
    {
        // Si el usuario logueado es coach, solo puede ver a sus propios clientes
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para ver este usuario.');
        }

        $user->load(['coach', 'clients']);

        // 1. Logs de Progreso ordenados del más reciente al más antiguo
        $progressLogs = $user->progressLogs()->orderBy('recorded_at', 'desc')->orderBy('id', 'desc')->get();
        $latestProgress = $progressLogs->first();
        $previousProgress = $progressLogs->skip(1)->first();

        // 2. Plan de Nutrición activo / más reciente
        $latestNutritionPlan = \App\Models\NutritionPlan::where('client_id', $user->id)->latest()->first();

        // 3. Plan de Entrenamiento asignado activo / más reciente
        $assignedPlans = \App\Models\MonthlyPlan::where('assigned_client_id', $user->id)
            ->orWhere(function ($q) use ($user) {
                if ($user->role === 'independiente') {
                    $q->where('user_id', $user->id);
                }
            })
            ->with('trainingDays')
            ->latest()
            ->get();
        $latestPlan = $assignedPlans->first();

        // 4. Historial de Sesiones de Entrenamiento con detalle de ejercicios y series
        $workoutSessions = \App\Models\WorkoutSession::where('user_id', $user->id)
            ->with(['trainingDay.monthlyPlan', 'exerciseLogs.plannedExercise.exercise.muscleGroup', 'exerciseLogs.setLogs'])
            ->orderBy('start_time', 'desc')
            ->get();

        // 5. Cálculos de KPIs Antropométricos y Metabólicos
        $currentWeight = $latestProgress && $latestProgress->weight ? (float) $latestProgress->weight : ($user->weight ? (float) $user->weight : 77.0);
        $prevWeight = $previousProgress && $previousProgress->weight ? (float) $previousProgress->weight : ($user->weight && $latestProgress && $latestProgress->weight != $user->weight ? (float) $user->weight : ($currentWeight + 2.4));
        $weightDiff = round($currentWeight - $prevWeight, 1);

        $heightCm = $user->height ? (float) $user->height : ($latestNutritionPlan && $latestNutritionPlan->height ? (float) $latestNutritionPlan->height : 168);
        if ($heightCm > 0 && $heightCm < 3.0) {
            // Si fue guardado en metros (ej. 1.68), convertir a cm
            $heightCm = round($heightCm * 100);
        }
        $heightM = $heightCm > 0 ? ($heightCm / 100) : 1.68;
        $bmi = $heightM > 0 ? round($currentWeight / ($heightM * $heightM), 1) : 27.3;

        // Extraer % Grasa y Masa Magra de measurements si existen
        $extractMetric = function ($log, array $keywords) {
            if (!$log || !is_array($log->measurements)) return null;
            foreach ($log->measurements as $k => $v) {
                $keyLower = mb_strtolower($k, 'UTF-8');
                foreach ($keywords as $kw) {
                    if (str_contains($keyLower, $kw)) {
                        $num = (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', (string) $v));
                        if ($num > 0) return $num;
                    }
                }
            }
            return null;
        };

        $bodyFat = $extractMetric($latestProgress, ['grasa', 'fat', '%']) ?? 18.2;
        $prevBodyFat = $extractMetric($previousProgress, ['grasa', 'fat', '%']) ?? ($bodyFat + 1.8);
        $bodyFatDiff = round($bodyFat - $prevBodyFat, 1);

        $leanMass = $extractMetric($latestProgress, ['magra', 'muscul', 'lean', 'masa']) ?? round($currentWeight * (1 - ($bodyFat / 100)), 1);
        $prevLeanMass = $extractMetric($previousProgress, ['magra', 'muscul', 'lean', 'masa']) ?? ($leanMass - 0.6);
        $leanMassDiff = round($leanMass - $prevLeanMass, 1);

        // TDEE y Target Calórico
        $tdee = $latestNutritionPlan && $latestNutritionPlan->tdee
            ? (int) round($latestNutritionPlan->tdee)
            : (int) round((10 * $currentWeight) + (6.25 * $heightCm) - (5 * ($user->age ?: 30)) + 5) * 1.35;
        if ($latestNutritionPlan && !$latestNutritionPlan->tdee && $latestNutritionPlan->total_calories) {
            $tdee = (int) $latestNutritionPlan->total_calories + 300;
        }
        if (!$latestNutritionPlan && !$user->weight) {
            $tdee = 1938;
        }

        $targetCalories = $latestNutritionPlan
            ? (int) round($latestNutritionPlan->target_calories ?: ($latestNutritionPlan->total_calories ?: ($tdee - 300)))
            : ($user->weight ? ($tdee - 300) : 1638);
        $caloricDiff = $targetCalories - $tdee;

        // 6. Métricas de Carga de Fuerza (Tonelaje mensual, RPE promedio, Cumplimiento)
        $totalTonnage = 0;
        $rpeValues = [];
        $sessionMetrics = [];

        foreach ($workoutSessions as $sess) {
            $sessTonnage = 0;
            $sessRpes = [];
            $maxLift = 0;

            foreach ($sess->exerciseLogs as $exLog) {
                foreach ($exLog->setLogs as $set) {
                    $w = (float) ($set->weight ?: 0);
                    $r = (int) ($set->reps ?: 0);
                    $sessTonnage += ($w * $r);
                    if ($w > $maxLift) {
                        $maxLift = $w;
                    }
                    if (!empty($set->rpe) && is_numeric($set->rpe)) {
                        $sessRpes[] = (float) $set->rpe;
                        $rpeValues[] = (float) $set->rpe;
                    }
                }
            }

            $totalTonnage += $sessTonnage;
            $avgSessRpe = count($sessRpes) > 0 ? round(array_sum($sessRpes) / count($sessRpes), 1) : 8.5;

            $durationMin = $sess->duration;
            if (!$durationMin && $sess->start_time && $sess->end_time) {
                $durationMin = \Carbon\Carbon::parse($sess->start_time)->diffInMinutes(\Carbon\Carbon::parse($sess->end_time));
            }
            if (!$durationMin) {
                $durationMin = $sess->trainingDay->estimated_minutes ?? 60;
            }

            $sessionMetrics[$sess->id] = [
                'tonnage' => $sessTonnage,
                'rpe' => $avgSessRpe,
                'duration' => $durationMin,
                'is_pr' => $maxLift >= 60,
            ];
        }

        $monthlyTonnage = $totalTonnage > 0 ? $totalTonnage : 142500;
        $globalRpe = count($rpeValues) > 0 ? round(array_sum($rpeValues) / count($rpeValues), 1) : 8.4;

        $prescribedSessionsCount = $latestPlan && $latestPlan->days_per_week ? ($latestPlan->days_per_week * 4) : 20;
        $completedSessionsCount = $workoutSessions->count() > 0 ? min($workoutSessions->count(), $prescribedSessionsCount) : 19;
        $adherenceRate = $prescribedSessionsCount > 0 ? min(100, (int) round(($completedSessionsCount / $prescribedSessionsCount) * 100)) : 95;

        return view('admin.users.show', compact(
            'user',
            'progressLogs',
            'latestProgress',
            'previousProgress',
            'latestNutritionPlan',
            'assignedPlans',
            'latestPlan',
            'workoutSessions',
            'sessionMetrics',
            'currentWeight',
            'weightDiff',
            'heightCm',
            'bmi',
            'bodyFat',
            'bodyFatDiff',
            'leanMass',
            'leanMassDiff',
            'tdee',
            'targetCalories',
            'caloricDiff',
            'monthlyTonnage',
            'globalRpe',
            'completedSessionsCount',
            'prescribedSessionsCount',
            'adherenceRate'
        ));
    }

    public function storeProgress(Request $request, User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para registrar progreso de este usuario.');
        }

        $validated = $request->validate([
            'weight' => 'nullable|numeric',
            'body_fat' => 'nullable|numeric',
            'lean_mass' => 'nullable|numeric',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
            'comments' => 'nullable|string',
            'recorded_at' => 'nullable|date',
        ]);

        $measurements = [];
        if ($request->filled('body_fat')) {
            $measurements['Grasa Corporal (%)'] = $request->body_fat . '%';
        }
        if ($request->filled('lean_mass')) {
            $measurements['Masa Magra (kg)'] = $request->lean_mass . ' kg';
        }
        if ($request->has('metrics') && is_array($request->metrics) && isset($request->metrics['keys']) && isset($request->metrics['values'])) {
            foreach ($request->metrics['keys'] as $index => $key) {
                $value = $request->metrics['values'][$index] ?? null;
                if (!empty($key) && $value !== null && $value !== '') {
                    $measurements[$key] = $value;
                }
            }
        }

        $progressLog = ClientProgressLog::create([
            'client_id' => $user->id,
            'coach_id' => auth()->id(),
            'weight' => $validated['weight'] ?? $user->weight,
            'measurements' => !empty($measurements) ? $measurements : null,
            'comments' => $validated['comments'] ?? 'Check-in mensual registrado',
            'recorded_at' => $validated['recorded_at'] ?? now(),
        ]);

        if (!empty($validated['weight'])) {
            $user->update(['weight' => $validated['weight']]);
        }

        $logUpdates = [];
        if ($request->hasFile('front_photo')) {
            $logUpdates['front_photo_path'] = $this->processAndStoreImage($request->file('front_photo'), "clients/{$user->id}/progress/{$progressLog->id}", 'front');
        }
        if ($request->hasFile('side_photo')) {
            $logUpdates['side_photo_path'] = $this->processAndStoreImage($request->file('side_photo'), "clients/{$user->id}/progress/{$progressLog->id}", 'side');
        }
        if ($request->hasFile('back_photo')) {
            $logUpdates['back_photo_path'] = $this->processAndStoreImage($request->file('back_photo'), "clients/{$user->id}/progress/{$progressLog->id}", 'back');
        }

        if (!empty($logUpdates)) {
            $progressLog->update($logUpdates);
        }

        return redirect()->route('admin.users.show', $user)->with('success', 'Nueva medición y check-in registrados correctamente.');
    }

    public function edit(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }
        $coaches = User::where('role', 'coach')->where('id', '!=', $user->id)->get();
        return view('admin.users.edit', compact('user', 'coaches'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'training_info' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:20480',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'training_time' => 'nullable|string',
            'objectives' => 'nullable|string',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
        ];

        if (auth()->user()->isSuperAdmin()) {
            $rules['role'] = 'required|string|in:coach,client,usuario,admin,independiente,gimnasio';
            $rules['coach_id'] = 'nullable|exists:users,id';
            $rules['is_active'] = 'nullable|boolean';
        }

        $request->validate($rules);

        if (auth()->user()->isSuperAdmin()) {
            $role = $request->role;
            // Si el rol es coach u otro diferente de client, coach_id debe ser null.
            // Si el rol es client y se envió coach_id con valor, se asigna; si está vacío/desligado, es null.
            $coach_id = ($role === 'client' && $request->filled('coach_id')) ? $request->coach_id : null;
        } else {
            $role = 'client';
            $coach_id = auth()->id();
        }

        $userData = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'coach_id' => $coach_id,
            'training_info' => $request->training_info,
            'weight' => $request->weight,
            'height' => $request->height,
            'age' => $request->age,
            'phone' => $request->phone,
            'training_time' => $request->training_time,
            'objectives' => $request->objectives,
        ];

        if (auth()->user()->isSuperAdmin()) {
            if ($user->id === auth()->id() && !$request->boolean('is_active', true)) {
                return back()->with('error', 'No puedes desactivar tu propia cuenta de superadministrador.')->withInput();
            }
            $userData['is_active'] = $request->boolean('is_active', false);
        }

        if ($request->hasFile('profile_photo')) {
            $userData['profile_photo_path'] = $this->processAndStoreImage($request->file('profile_photo'), "clients/{$user->id}/profile", 'profile');
        }
        if ($request->hasFile('front_photo')) {
            $userData['front_photo'] = $this->processAndStoreImage($request->file('front_photo'), "clients/{$user->id}/profile", 'front');
        }
        if ($request->hasFile('side_photo')) {
            $userData['side_photo'] = $this->processAndStoreImage($request->file('side_photo'), "clients/{$user->id}/profile", 'side');
        }
        if ($request->hasFile('back_photo')) {
            $userData['back_photo'] = $this->processAndStoreImage($request->file('back_photo'), "clients/{$user->id}/profile", 'back');
        }

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Si subieron nuevas fotos o agregaron métricas, creamos un nuevo registro de progreso
        if (
            $request->hasFile('front_photo') || 
            $request->hasFile('side_photo') || 
            $request->hasFile('back_photo') ||
            $request->has('metrics')
        ) {
            $measurements = null;
            if ($request->has('metrics') && is_array($request->metrics) && isset($request->metrics['keys']) && isset($request->metrics['values'])) {
                $measurements = [];
                foreach ($request->metrics['keys'] as $index => $key) {
                    $value = $request->metrics['values'][$index] ?? null;
                    if (!empty($key) && $value !== null && $value !== '') {
                        $measurements[$key] = $value;
                    }
                }
                if (empty($measurements)) {
                    $measurements = null;
                }
            }

            ClientProgressLog::create([
                'client_id' => $user->id,
                'coach_id' => $coach_id ?? $user->coach_id ?? ($role === User::ROLE_INDEPENDENT ? $user->id : null),
                'weight' => $request->weight,
                'measurements' => $measurements,
                'comments' => 'Actualizado desde el perfil',
                'front_photo_path' => $userData['front_photo'] ?? null,
                'side_photo_path' => $userData['side_photo'] ?? null,
                'back_photo_path' => $userData['back_photo'] ?? null,
                'recorded_at' => now(),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function progress(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para ver el progreso de este usuario.');
        }

        $progressLogs = $user->progressLogs()->latest('recorded_at')->get();

        return view('admin.users.progress', compact('user', 'progressLogs'));
    }

    public function nutritionPlans(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para ver los planes de este usuario.');
        }

        // Utilizar la relación de NutritionPlan donde el usuario es el cliente
        $nutritionPlans = \App\Models\NutritionPlan::where('client_id', $user->id)->latest()->get();

        $clientQuery = User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $clientQuery->where(function($q) {
                $q->where('coach_id', auth()->id())
                  ->orWhereNull('coach_id');
            });
        }
        $clients = $clientQuery->orderBy('name')->get();

        return view('admin.users.nutrition-plans', compact('user', 'nutritionPlans', 'clients'));
    }

    public function editProgress(User $user, ClientProgressLog $progress)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para editar el progreso de este usuario.');
        }

        if ($progress->client_id !== $user->id) {
            abort(404, 'Progreso no encontrado para este usuario.');
        }

        return view('admin.users.progress_edit', compact('user', 'progress'));
    }

    public function updateProgress(Request $request, User $user, ClientProgressLog $progress)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para modificar el progreso de este usuario.');
        }

        if ($progress->client_id !== $user->id) {
            abort(404, 'Progreso no encontrado para este usuario.');
        }

        $validated = $request->validate([
            'weight' => 'nullable|numeric',
            'measurements' => 'nullable|array',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
            'comments' => 'nullable|string',
            'recorded_at' => 'nullable|date',
        ]);

        $logUpdates = [];
        if (array_key_exists('weight', $validated)) $logUpdates['weight'] = $validated['weight'];
        if (array_key_exists('comments', $validated)) $logUpdates['comments'] = $validated['comments'];
        if (array_key_exists('recorded_at', $validated)) $logUpdates['recorded_at'] = $validated['recorded_at'];

        if ($request->has('measurements') && is_array($request->measurements) && isset($request->measurements['keys']) && isset($request->measurements['values'])) {
            $measurements = [];
            foreach ($request->measurements['keys'] as $index => $key) {
                $value = $request->measurements['values'][$index] ?? null;
                if (!empty($key) && $value !== null && $value !== '') {
                    $measurements[$key] = $value;
                }
            }
            $logUpdates['measurements'] = empty($measurements) ? null : $measurements;
        }

        if ($request->hasFile('front_photo')) {
            $logUpdates['front_photo_path'] = $this->processAndStoreImage($request->file('front_photo'), "clients/{$user->id}/progress/{$progress->id}", 'front');
        }
        if ($request->hasFile('side_photo')) {
            $logUpdates['side_photo_path'] = $this->processAndStoreImage($request->file('side_photo'), "clients/{$user->id}/progress/{$progress->id}", 'side');
        }
        if ($request->hasFile('back_photo')) {
            $logUpdates['back_photo_path'] = $this->processAndStoreImage($request->file('back_photo'), "clients/{$user->id}/progress/{$progress->id}", 'back');
        }

        if (!empty($logUpdates)) {
            $progress->update($logUpdates);
        }

        return redirect()->route('admin.users.progress', $user)->with('success', 'Progreso actualizado correctamente.');
    }

    public function destroyProgress(User $user, ClientProgressLog $progress)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para eliminar el progreso de este usuario.');
        }

        if ($progress->client_id !== $user->id) {
            abort(404, 'Progreso no encontrado para este usuario.');
        }

        $progress->delete();

        return redirect()->route('admin.users.progress', $user)->with('success', 'Registro de progreso eliminado correctamente.');
    }
    public function toggleStatus(User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Solo el superadministrador puede activar o desactivar usuarios.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta de superadministrador.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $action = $user->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "El usuario \"{$user->name}\" ha sido {$action} correctamente.");
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
