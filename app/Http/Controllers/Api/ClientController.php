<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
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

        if ($request->hasFile('profile_photo')) {
            $userData['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        if ($request->hasFile('front_photo')) {
            $userData['front_photo'] = $request->file('front_photo')->store('clients/photos', 'public');
        }
        if ($request->hasFile('side_photo')) {
            $userData['side_photo'] = $request->file('side_photo')->store('clients/photos', 'public');
        }
        if ($request->hasFile('back_photo')) {
            $userData['back_photo'] = $request->file('back_photo')->store('clients/photos', 'public');
        }

        $user = User::create($userData);

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
     * Remove the specified client from the coach.
     */
    public function destroy(Request $request, User $client)
    {
        // Ensure the client belongs to this coach
        if ($client->coach_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $client->update(['coach_id' => null]);

        return response()->json(['message' => 'Cliente desvinculado correctamente.']);
    }
}
