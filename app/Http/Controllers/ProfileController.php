<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'training_info' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:20480', // Max 20MB
            'logo' => 'nullable|image|max:20480', // Max 20MB
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($user->logo_path) {
                Storage::disk('public')->delete($user->logo_path);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $user->logo_path = $path;
        }

        $user->training_info = $validated['training_info'] ?? null;
        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
