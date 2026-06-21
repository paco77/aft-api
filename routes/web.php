<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (in_array(auth()->user()->role, ['admin', 'coach'])) {
            return redirect()->route('admin.dashboard');
        }
        return view('welcome');
    }
    return redirect()->route('login');
});

// Rutas de Autenticación
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class , 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class , 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class , 'logout'])->name('logout');

// Rutas de Recuperación de Contraseña
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'showForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class , 'showForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class , 'reset'])->name('password.update');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class , 'index'])->name('dashboard');

    // Rutas para Usuarios
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Rutas para Ejercicios
    Route::resource('exercises', \App\Http\Controllers\Admin\ExerciseController::class);
    Route::post('exercises/api-store', [\App\Http\Controllers\Admin\ExerciseController::class, 'apiStore'])->name('exercises.api-store');

    // Rutas para Planes
    Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class);
    Route::get('plans/{plan}/pdf', [\App\Http\Controllers\Admin\PlanController::class, 'exportPdf'])->name('plans.pdf');

    // Rutas para Planes de Alimentación
    Route::resource('nutrition-plans', \App\Http\Controllers\Admin\NutritionPlanController::class);
    Route::get('nutrition-plans/{nutrition_plan}/pdf', [\App\Http\Controllers\Admin\NutritionPlanController::class, 'exportPdf'])->name('nutrition-plans.pdf');

    // Rutas para Entrenamientos y Progreso
    Route::get('/users/{user}/workouts', [\App\Http\Controllers\Admin\WorkoutController::class, 'index'])->name('users.workouts');
    Route::get('/users/{user}/progress', [\App\Http\Controllers\Admin\UserController::class, 'progress'])->name('users.progress');
    Route::get('/users/{user}/nutrition-plans', [\App\Http\Controllers\Admin\UserController::class, 'nutritionPlans'])->name('users.nutrition-plans');
    Route::get('/workouts/{session}', [\App\Http\Controllers\Admin\WorkoutController::class, 'show'])->name('workouts.show');

    // Perfil de Coach
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class , 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');
});
