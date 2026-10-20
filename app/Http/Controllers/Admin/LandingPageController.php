<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    /**
     * Enforce Super Admin access.
     */
    protected function checkSuperAdmin(): void
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Acceso restringido: Solo el Super Administrador puede gestionar el Landing Page.');
        }
    }

    /**
     * Show form to edit landing page settings.
     */
    public function edit()
    {
        $this->checkSuperAdmin();

        $settings = LandingSetting::getAllSettings();
        $defaults = LandingSetting::defaults();
        $dbRows = LandingSetting::all()->keyBy('key');

        return view('admin.landing.edit', compact('settings', 'defaults', 'dbRows'));
    }

    /**
     * Update landing page settings.
     */
    public function update(Request $request)
    {
        $this->checkSuperAdmin();

        $defaults = LandingSetting::defaults();

        // 1. Process file uploads for image fields
        $uploadedImages = [];
        $imageKeys = ['hero_image_1', 'hero_image_2'];

        foreach ($imageKeys as $imgKey) {
            if ($request->hasFile($imgKey)) {
                $file = $request->file($imgKey);
                $path = $file->store('landing', 'public');
                $uploadedImages[$imgKey] = Storage::disk('public')->url($path);
            }
        }

        // 2. Identify boolean toggles
        $booleanKeys = [
            'show_app_downloads',
            'section_roles_enabled',
            'section_modules_enabled',
            'section_workflow_enabled',
            'section_philosophy_enabled',
            'section_final_cta_enabled',
        ];

        // 3. Save each setting
        foreach ($defaults as $key => $meta) {
            $value = null;

            // Handle image uploads
            if (isset($uploadedImages[$key])) {
                $value = $uploadedImages[$key];
            } elseif ($meta['type'] === 'image') {
                // If an image URL was manually typed/kept and no new file was uploaded
                $value = $request->input($key, $request->input($key . '_url', null));
            } elseif (in_array($key, $booleanKeys)) {
                // Checkbox toggle: if checked in request => '1', else => '0'
                $value = $request->has($key) ? '1' : '0';
            } else {
                $value = $request->input($key, '');
            }

            if ($value !== null) {
                LandingSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'type' => $meta['type'],
                        'group' => $meta['group'],
                        'label' => $meta['label'],
                    ]
                );
            }
        }

        // Clear cache so frontend reflects updates immediately
        LandingSetting::clearCache();

        return redirect()->route('admin.landing.edit')->with('success', '¡Configuración del Landing Page actualizada exitosamente!');
    }

    /**
     * Reset landing page settings to factory defaults.
     */
    public function resetDefaults()
    {
        $this->checkSuperAdmin();

        $defaults = LandingSetting::defaults();

        foreach ($defaults as $key => $meta) {
            LandingSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $meta['value'],
                    'type' => $meta['type'],
                    'group' => $meta['group'],
                    'label' => $meta['label'],
                ]
            );
        }

        LandingSetting::clearCache();

        return redirect()->route('admin.landing.edit')->with('success', 'El Landing Page ha sido restaurado a los valores predeterminados de fábrica.');
    }
}
