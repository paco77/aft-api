<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\ClientProgressLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Traits\ImageUploadTrait;

class ClientController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of clients assigned to the coach.
     */
    public function index(Request $request)
    {
        $clients = $request->user()->clients()->latest()->get();
        return UserResource::collection($clients);
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'training_time' => 'nullable|string',
            'objectives' => 'nullable|string',
            'measurements' => 'nullable|json',
            'profile_photo' => 'nullable|image|max:20480',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
        ]);

        $userData = [
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'coach_id' => $request->user()->id,
            'weight' => $validated['weight'] ?? null,
            'height' => $validated['height'] ?? null,
            'age' => $validated['age'] ?? null,
            'training_time' => $validated['training_time'] ?? null,
            'objectives' => $validated['objectives'] ?? null,
        ];

        $user = User::create($userData);

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

        // Create initial progress log if there's data for it
        if (
            isset($validated['weight']) || 
            isset($validated['measurements']) || 
            $request->hasFile('front_photo') || 
            $request->hasFile('side_photo') || 
            $request->hasFile('back_photo')
        ) {
            $logData = [
                'client_id' => $user->id,
                'coach_id' => $request->user()->id,
                'weight' => $validated['weight'] ?? null,
                'measurements' => isset($validated['measurements']) ? json_decode($validated['measurements'], true) : null,
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

        return new UserResource($user);
    }



    /**
     * Display the specified client.
     */
    public function show(Request $request, User $client)
    {
        // Ensure the client belongs to this coach
        if ($client->coach_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new UserResource($client);
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, User $client)
    {
        // Ensure the client belongs to this coach
        if ($client->coach_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $client->id,
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $client->id,
            'password' => 'nullable|string|min:8',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'training_time' => 'nullable|string',
            'objectives' => 'nullable|string',
            'training_info' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'profile_photo' => 'nullable|image|max:20480',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {
            if ($client->profile_photo_path) {
                Storage::delete($client->profile_photo_path);
            }
            $validated['profile_photo_path'] = $this->processAndStoreImage($request->file('profile_photo'), "clients/{$client->id}/profile", 'profile');
        }
        if ($request->hasFile('front_photo')) {
            if ($client->front_photo) {
                Storage::delete($client->front_photo);
            }
            $validated['front_photo'] = $this->processAndStoreImage($request->file('front_photo'), "clients/{$client->id}/profile", 'front');
        }
        if ($request->hasFile('side_photo')) {
            if ($client->side_photo) {
                Storage::delete($client->side_photo);
            }
            $validated['side_photo'] = $this->processAndStoreImage($request->file('side_photo'), "clients/{$client->id}/profile", 'side');
        }
        if ($request->hasFile('back_photo')) {
            if ($client->back_photo) {
                Storage::delete($client->back_photo);
            }
            $validated['back_photo'] = $this->processAndStoreImage($request->file('back_photo'), "clients/{$client->id}/profile", 'back');
        }

        unset($validated['profile_photo']);

        $client->update($validated);

        return new UserResource($client);
    }

    /**
     * Remove the specified client from the coach.
     */
    public function destroy(Request $request, User $client)
    {
        // Ensure the client belongs to this coach
        if ($client->coach_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $client->delete();

        return response()->json(['message' => 'Cliente y toda su información eliminados correctamente.']);
    }
}
