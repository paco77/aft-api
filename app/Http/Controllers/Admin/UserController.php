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

    public function show(Request $request, User $user)
    {
        // Si el usuario logueado es coach, solo puede ver a sus propios clientes
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para ver este usuario.');
        }

        $user->load(['coach', 'clients']);

        // 1. Logs de Progreso ordenados del más reciente al más antiguo
        $progressLogs = $user->progressLogs()->orderBy('recorded_at', 'desc')->orderBy('id', 'desc')->get();
        $initialProgress = $progressLogs->last();

        // 2. Planes de Nutrición y Planes de Entrenamiento
        $nutritionPlans = \App\Models\NutritionPlan::where('client_id', $user->id)->latest()->get();
        $latestNutritionPlan = $nutritionPlans->first();
        $initialNutritionPlan = $nutritionPlans->last();

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

        // 3. Historial completo de Sesiones de Entrenamiento del usuario
        $allWorkoutSessions = \App\Models\WorkoutSession::where('user_id', $user->id)
            ->with(['trainingDay.monthlyPlan', 'exerciseLogs.plannedExercise.exercise.muscleGroup', 'exerciseLogs.setLogs'])
            ->orderBy('start_time', 'desc')
            ->get();

        // 4. Construir lista de meses disponibles para analizar (basado en registros reales + mes actual)
        $availableMonths = collect();
        $spanishMonths = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $spanishMonthsReverse = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
            'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
            'septiembre' => 9, 'setiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12
        ];

        $addMonthKey = function ($carbonDate) use (&$availableMonths, $spanishMonths) {
            if (!$carbonDate) return;
            $key = $carbonDate->format('Y-m');
            if (!$availableMonths->has($key)) {
                $mNum = (int) $carbonDate->format('n');
                $availableMonths->put($key, [
                    'key' => $key,
                    'year' => (int) $carbonDate->format('Y'),
                    'month' => $mNum,
                    'label' => ($spanishMonths[$mNum] ?? $carbonDate->format('F')) . ' ' . $carbonDate->format('Y'),
                ]);
            }
        };

        // Mes de registro inicial del usuario
        if ($user->created_at) {
            $addMonthKey(\Carbon\Carbon::parse($user->created_at));
        }
        // Meses de logs de progreso
        foreach ($progressLogs as $pLog) {
            if ($pLog->recorded_at) {
                $addMonthKey(\Carbon\Carbon::parse($pLog->recorded_at));
            }
        }
        // Meses de sesiones de entrenamiento
        foreach ($allWorkoutSessions as $wSess) {
            if ($wSess->start_time) {
                $addMonthKey(\Carbon\Carbon::parse($wSess->start_time));
            }
        }
        // Meses de planes mensuales asignados
        foreach ($assignedPlans as $mPlan) {
            $mLower = mb_strtolower(trim((string) $mPlan->month), 'UTF-8');
            $mNum = $spanishMonthsReverse[$mLower] ?? (is_numeric($mPlan->month) ? (int) $mPlan->month : null);
            $yNum = is_numeric($mPlan->year) ? (int) $mPlan->year : (int) now()->format('Y');
            if ($mNum && $mNum >= 1 && $mNum <= 12) {
                $addMonthKey(\Carbon\Carbon::createFromDate($yNum, $mNum, 1));
            } elseif ($mPlan->created_at) {
                $addMonthKey(\Carbon\Carbon::parse($mPlan->created_at));
            }
        }
        // Asegurar siempre el mes actual
        $addMonthKey(now());

        // Ordenar meses cronológicamente (de más antiguo a más reciente)
        $availableMonths = $availableMonths->sortKeys()->values();

        // Mes seleccionado por el usuario (por defecto el más reciente disponible)
        $selectedMonthKey = $request->query('month', $availableMonths->last()['key'] ?? now()->format('Y-m'));
        if (!$availableMonths->contains('key', $selectedMonthKey)) {
            $selectedMonthKey = $availableMonths->last()['key'] ?? now()->format('Y-m');
        }
        $selectedMonthObj = $availableMonths->firstWhere('key', $selectedMonthKey);
        $selectedDateStart = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonthKey)->startOfMonth();
        $selectedDateEnd = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonthKey)->endOfMonth();

        // 5. SECCIÓN 1: DATOS INICIALES DE REGISTRO DEL USUARIO
        $initialWeight = $user->weight ? (float) $user->weight : ($initialProgress && $initialProgress->weight ? (float) $initialProgress->weight : 0);
        $heightCm = $user->height ? (float) $user->height : ($initialNutritionPlan && $initialNutritionPlan->height ? (float) $initialNutritionPlan->height : 0);
        if ($heightCm > 0 && $heightCm < 3.0) {
            $heightCm = round($heightCm * 100);
        }
        $heightM = $heightCm > 0 ? ($heightCm / 100) : 0;
        $initialBmi = ($heightM > 0 && $initialWeight > 0) ? round($initialWeight / ($heightM * $heightM), 1) : 0;

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

        $initialBodyFat = $extractMetric($initialProgress, ['grasa', 'fat', '%']);
        $initialLeanMass = $extractMetric($initialProgress, ['magra', 'muscul', 'lean', 'masa']);
        if (!$initialLeanMass && $initialWeight > 0 && $initialBodyFat) {
            $initialLeanMass = round($initialWeight * (1 - ($initialBodyFat / 100)), 1);
        }

        $initialTdee = $initialNutritionPlan && $initialNutritionPlan->tdee
            ? (int) round($initialNutritionPlan->tdee)
            : (($initialWeight > 0 && $heightCm > 0)
                ? (int) round(((10 * $initialWeight) + (6.25 * $heightCm) - (5 * ($user->age ?: 30)) + 5) * 1.35)
                : 0);
        $initialTargetCalories = $initialNutritionPlan
            ? (int) round($initialNutritionPlan->target_calories ?: ($initialNutritionPlan->total_calories ?: 0))
            : ($initialTdee > 0 ? ($initialTdee - 300) : 0);

        // 6. SECCIÓN 2, 3 y 4: DATOS DEL MES SELECCIONADO ($selectedMonthKey)
        // Logs de progreso registrados en ese mes
        $monthProgressLogs = $progressLogs->filter(function ($log) use ($selectedMonthKey) {
            return $log->recorded_at && \Carbon\Carbon::parse($log->recorded_at)->format('Y-m') === $selectedMonthKey;
        })->values();

        // El registro representativo del mes seleccionado (o el último hasta ese mes si no hubo en ese mes exacto)
        $selectedProgress = $monthProgressLogs->first() ?: $progressLogs->filter(function ($log) use ($selectedDateEnd) {
            return $log->recorded_at && \Carbon\Carbon::parse($log->recorded_at)->lte($selectedDateEnd);
        })->first();

        // Registro inmediato anterior al mes seleccionado para comparar deltas y fotos
        $previousProgress = $progressLogs->filter(function ($log) use ($selectedDateStart, $selectedProgress) {
            if (!$log->recorded_at) return false;
            if ($selectedProgress && $log->id === $selectedProgress->id) return false;
            return \Carbon\Carbon::parse($log->recorded_at)->lt($selectedDateStart);
        })->first();

        $monthWeight = $selectedProgress && $selectedProgress->weight
            ? (float) $selectedProgress->weight
            : $initialWeight;
        $prevMonthWeight = $previousProgress && $previousProgress->weight
            ? (float) $previousProgress->weight
            : $initialWeight;
        $weightDiff = ($monthWeight > 0 && $prevMonthWeight > 0) ? round($monthWeight - $prevMonthWeight, 1) : 0;

        $monthBodyFat = $extractMetric($selectedProgress, ['grasa', 'fat', '%']) ?? $initialBodyFat;
        $prevMonthBodyFat = $extractMetric($previousProgress, ['grasa', 'fat', '%']) ?? $initialBodyFat;
        $bodyFatDiff = ($monthBodyFat && $prevMonthBodyFat) ? round($monthBodyFat - $prevMonthBodyFat, 1) : 0;

        $monthLeanMass = $extractMetric($selectedProgress, ['magra', 'muscul', 'lean', 'masa']);
        if (!$monthLeanMass && $monthWeight > 0 && $monthBodyFat) {
            $monthLeanMass = round($monthWeight * (1 - ($monthBodyFat / 100)), 1);
        }

        // Sesiones de entrenamiento realizadas en el mes seleccionado
        $workoutSessions = $allWorkoutSessions->filter(function ($sess) use ($selectedMonthKey) {
            return $sess->start_time && \Carbon\Carbon::parse($sess->start_time)->format('Y-m') === $selectedMonthKey;
        })->values();

        // Si en el mes seleccionado no hay sesiones pero el usuario tiene sesiones en otros meses,
        // guardamos también el total para informar o permitir ver todas
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
            $avgSessRpe = count($sessRpes) > 0 ? round(array_sum($sessRpes) / count($sessRpes), 1) : null;

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

        $monthlyTonnage = $totalTonnage;
        $globalRpe = count($rpeValues) > 0 ? round(array_sum($rpeValues) / count($rpeValues), 1) : null;

        // Plan asignado de ese mes (o el más reciente)
        $monthPlan = $assignedPlans->first(function ($p) use ($selectedMonthObj, $spanishMonthsReverse) {
            if (!$selectedMonthObj) return false;
            $mLower = mb_strtolower(trim((string) $p->month), 'UTF-8');
            $mNum = $spanishMonthsReverse[$mLower] ?? (is_numeric($p->month) ? (int) $p->month : null);
            return $mNum === $selectedMonthObj['month'] && (int) $p->year === $selectedMonthObj['year'];
        }) ?: $latestPlan;

        $prescribedSessionsCount = $monthPlan && $monthPlan->days_per_week ? ($monthPlan->days_per_week * 4) : 20;
        $completedSessionsCount = $workoutSessions->count();
        $adherenceRate = $prescribedSessionsCount > 0 ? min(100, (int) round(($completedSessionsCount / $prescribedSessionsCount) * 100)) : 0;

        return view('admin.users.show', compact(
            'user',
            'progressLogs',
            'initialProgress',
            'monthProgressLogs',
            'selectedProgress',
            'previousProgress',
            'latestNutritionPlan',
            'initialNutritionPlan',
            'assignedPlans',
            'latestPlan',
            'monthPlan',
            'allWorkoutSessions',
            'workoutSessions',
            'sessionMetrics',
            'availableMonths',
            'selectedMonthKey',
            'selectedMonthObj',
            'initialWeight',
            'heightCm',
            'initialBmi',
            'initialBodyFat',
            'initialLeanMass',
            'initialTdee',
            'initialTargetCalories',
            'monthWeight',
            'prevMonthWeight',
            'weightDiff',
            'monthBodyFat',
            'bodyFatDiff',
            'monthLeanMass',
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
