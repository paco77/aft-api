<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\MuscleGroupController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\WorkoutController;
use App\Http\Controllers\Api\NutritionPlanController;

// Auth Routes
Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);
Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

// Password Reset Routes
Route::post('/forgot-password', [PasswordResetController::class , 'forgotPassword']);
Route::post('/verify-reset-code', [PasswordResetController::class , 'verifyCode']);
Route::post('/reset-password', [PasswordResetController::class , 'resetPassword']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class , 'logout']);
    Route::get('/me', [AuthController::class , 'me']);
    Route::put('/profile', [AuthController::class , 'updateProfile']);

    Route::apiResource('clients', ClientController::class);
    Route::get('/clients/{client}/progress', [\App\Http\Controllers\Api\ClientProgressController::class, 'index']);
    Route::post('/clients/{client}/progress', [\App\Http\Controllers\Api\ClientProgressController::class, 'store']);

    // Exercises & Muscle Groups
    Route::apiResource('exercises', ExerciseController::class);
    Route::apiResource('muscle-groups', MuscleGroupController::class)->only(['index', 'show']);

    // Plans
    Route::get('/plans/active', [PlanController::class , 'activePlan']);
    Route::apiResource('plans', PlanController::class);

    // Workouts
    Route::get('/workouts/history', [WorkoutController::class , 'history']);
    Route::post('/workouts/bulk', [WorkoutController::class , 'bulkLog']);
    Route::post('/workouts/start', [WorkoutController::class , 'startSession']);
    Route::post('/workouts/{session}/log-exercise', [WorkoutController::class , 'logExercise']);
    Route::post('/exercise-logs/{log}/log-set', [WorkoutController::class , 'logSet']);
    Route::post('/workouts/{session}/finish', [WorkoutController::class , 'finishSession']);

    // USDA FoodData Central
    Route::get('/usda/search', [\App\Http\Controllers\Api\UsdaController::class , 'search']);

    // FatSecret
    Route::get('/fatsecret/search', [\App\Http\Controllers\Api\FatSecretController::class , 'search']);

    // Nutrition Plans
    Route::apiResource('nutrition-plans', NutritionPlanController::class);

    // Steps
    Route::get('/steps', [\App\Http\Controllers\Api\StepLogController::class, 'index']);
    Route::post('/steps', [\App\Http\Controllers\Api\StepLogController::class, 'store']);
});
