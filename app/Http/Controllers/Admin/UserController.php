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
            'training_time' => 'nullable|string',
            'objectives' => 'nullable|string',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['role'] = 'required|string|in:coach,client,usuario,admin';
            $rules['coach_id'] = 'nullable|exists:users,id';
            $rules['is_active'] = 'nullable|boolean';
        }

        $request->validate($rules);

        if (auth()->user()->role === 'admin') {
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
                'coach_id' => $coach_id,
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

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $user)
    {
        // Si el usuario logueado es coach, solo puede ver a sus propios clientes
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para ver este usuario.');
        }

        return view('admin.users.show', compact('user'));
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
            'training_time' => 'nullable|string',
            'objectives' => 'nullable|string',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['role'] = 'required|string|in:coach,client,usuario,admin';
            $rules['coach_id'] = 'nullable|exists:users,id';
            $rules['is_active'] = 'nullable|boolean';
        }

        $request->validate($rules);

        if (auth()->user()->role === 'admin') {
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
            'training_time' => $request->training_time,
            'objectives' => $request->objectives,
        ];

        if (auth()->user()->role === 'admin') {
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
                'coach_id' => $coach_id ?? $user->coach_id,
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

        return view('admin.users.nutrition-plans', compact('user', 'nutritionPlans'));
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
    public function destroy(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
