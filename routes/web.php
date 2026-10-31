<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (in_array(auth()->user()->role, ['admin', 'super_admin', 'coach', 'gimnasio', 'independiente'])) {
            // Si el admin no está pidiendo vista previa explícita, redirigir a dashboard
            if (!request()->has('preview')) {
                return redirect()->route('admin.dashboard');
            }
        }
    }
    $settings = \App\Models\LandingSetting::getAllSettings();
    return view('welcome', compact('settings'));
})->name('landing.home');

// Rutas de Autenticación
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class , 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class , 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class , 'logout'])->name('logout');

// Rutas de Registro (Coach, Independiente, Gimnasio)
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class , 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class , 'register']);

// Rutas de Recuperación de Contraseña
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'showForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class , 'showForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class , 'reset'])->name('password.update');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class , 'index'])->name('dashboard');

    // Rutas para Usuarios
    Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Rutas para Ejercicios
    Route::resource('exercises', \App\Http\Controllers\Admin\ExerciseController::class);
    Route::post('exercises/api-store', [\App\Http\Controllers\Admin\ExerciseController::class, 'apiStore'])->name('exercises.api-store');

    // Rutas para Planes
    Route::post('plans/{plan}/duplicate', [\App\Http\Controllers\Admin\PlanController::class, 'duplicate'])->name('plans.duplicate');
    Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class);
    Route::get('plans/{plan}/pdf', [\App\Http\Controllers\Admin\PlanController::class, 'exportPdf'])->name('plans.pdf');

    // Rutas para Planes de Alimentación
    Route::get('foods/search', [\App\Http\Controllers\Admin\NutritionPlanController::class, 'searchFoods'])->name('foods.search');
    Route::get('foods/sync-catalog', [\App\Http\Controllers\Admin\NutritionPlanController::class, 'syncCatalog'])->name('foods.sync');
    Route::post('nutrition-plans/{nutrition_plan}/duplicate', [\App\Http\Controllers\Admin\NutritionPlanController::class, 'duplicate'])->name('nutrition-plans.duplicate');
    Route::resource('nutrition-plans', \App\Http\Controllers\Admin\NutritionPlanController::class);
    Route::get('nutrition-plans/{nutrition_plan}/pdf', [\App\Http\Controllers\Admin\NutritionPlanController::class, 'exportPdf'])->name('nutrition-plans.pdf');

    // FatSecret Web Route for Admin Panel
    Route::get('/fatsecret/search', [\App\Http\Controllers\Api\FatSecretController::class, 'search'])->name('fatsecret.search');

    // Rutas para Actividades al Aire Libre (Cardio GPS)
    Route::resource('outdoor-activities', \App\Http\Controllers\Admin\OutdoorActivityController::class)->only(['index', 'destroy']);

    // Rutas para Entrenamientos y Progreso
    Route::get('/users/{user}/workouts', [\App\Http\Controllers\Admin\WorkoutController::class, 'index'])->name('users.workouts');
    Route::get('/users/{user}/progress', [\App\Http\Controllers\Admin\UserController::class, 'progress'])->name('users.progress');
    Route::post('/users/{user}/progress', [\App\Http\Controllers\Admin\UserController::class, 'storeProgress'])->name('users.progress.store');
    Route::get('/users/{user}/progress/{progress}/edit', [\App\Http\Controllers\Admin\UserController::class, 'editProgress'])->name('users.progress.edit');
    Route::put('/users/{user}/progress/{progress}', [\App\Http\Controllers\Admin\UserController::class, 'updateProgress'])->name('users.progress.update');
    Route::delete('/users/{user}/progress/{progress}', [\App\Http\Controllers\Admin\UserController::class, 'destroyProgress'])->name('users.progress.destroy');
    Route::get('/users/{user}/nutrition-plans', [\App\Http\Controllers\Admin\UserController::class, 'nutritionPlans'])->name('users.nutrition-plans');
    Route::get('/workouts/{session}', [\App\Http\Controllers\Admin\WorkoutController::class, 'show'])->name('workouts.show');

    // Rutas para Rutinas de Gimnasio (No ligadas a planes mensuales)
    Route::get('gym-routines/qr', [\App\Http\Controllers\Admin\GymRoutineController::class, 'qr'])->name('gym-routines.qr');
    Route::resource('gym-routines', \App\Http\Controllers\Admin\GymRoutineController::class);

    // Perfil de Coach / Gimnasio
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class , 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');

    // Gestión del Landing Page (Exclusivo Super Administrador)
    Route::get('/landing', [\App\Http\Controllers\Admin\LandingPageController::class, 'edit'])->name('landing.edit');
    Route::put('/landing', [\App\Http\Controllers\Admin\LandingPageController::class, 'update'])->name('landing.update');
    Route::post('/landing/reset', [\App\Http\Controllers\Admin\LandingPageController::class, 'resetDefaults'])->name('landing.reset');
});

// Rutas Públicas de Gimnasio (Acceso mediante escaneo de Código QR)
Route::get('/gimnasio/{identifier}', [\App\Http\Controllers\GymPublicController::class, 'showRoutines'])->name('gym.routines');
Route::get('/gimnasio/{identifier}/rutina/{trainingDay}/entrenar', [\App\Http\Controllers\GymPublicController::class, 'liveWorkout'])->name('gym.workout');
Route::post('/gimnasio/workout/save', [\App\Http\Controllers\GymPublicController::class, 'saveWorkout'])->name('gym.workout.save');

