<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'training_info' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048', // Max 2MB
            'logo' => 'nullable|image|max:2048', // Max 2MB
        ]);

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

        $user->training_info = $validated['training_info'];
        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
