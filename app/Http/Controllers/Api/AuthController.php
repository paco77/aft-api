<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageUploadTrait;

class AuthController extends Controller
{
    use ImageUploadTrait;
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'string|in:coach,client,independiente',
        ]);

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'client',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->load('coach');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->login)
            ->orWhere('email', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'login' => ['Usuario Inactivo, favor de contactar al Administrador'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->load('coach');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user()->load('coach'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'training_time' => 'nullable|string',
            'objectives' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'training_info' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:20480', // Max 20MB
            'measurements' => 'nullable',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('s3')->delete($user->profile_photo_path);
            }

            $path = $this->processAndStoreImage($request->file('profile_photo'), "profile-photos", 'profile');
            $user->profile_photo_path = $path;
        }

        // Remove profile_photo from data before updating other fields
        unset($data['profile_photo']);

        $user->update(array_filter($data, fn($v) => !is_null($v)));
        $user->save();

        if ($request->has('measurements')) {
            $measurements = $request->measurements;
            if (is_string($measurements)) {
                $measurements = json_decode($measurements, true);
            }
            if (is_array($measurements)) {
                $latestLog = $user->progressLogs()->latest('recorded_at')->first();
                if ($latestLog) {
                    $logUpdates = ['measurements' => $measurements];
                    if (isset($data['weight'])) {
                        $logUpdates['weight'] = $data['weight'];
                    }
                    $latestLog->update($logUpdates);
                } else {
                    $user->progressLogs()->create([
                        'coach_id' => $user->coach_id ?? $user->id,
                        'weight' => $data['weight'] ?? $user->weight,
                        'measurements' => $measurements,
                        'comments' => 'Registro inicial',
                        'recorded_at' => now(),
                    ]);
                }
            }
        }

        return new UserResource($user);
    }
}
