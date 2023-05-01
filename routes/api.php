<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExercisesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get("/", function () {
    return json_encode('Welcome to the Work Out!');
});

Route::put("/users/{id}", [UserController::class, 'update']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get("/users", [UserController::class, 'index']);
    Route::get("/users/{id}", [UserController::class, 'show']);
    Route::post("/users", [UserController::class, 'store']);
    Route::delete("/users/{id}", [UserController::class, 'destroy']);

    Route::get("/workouts", [WorkoutController::class, 'index']);
    Route::post("/workouts", [WorkoutController::class, 'store']);
    Route::get("/workouts/{id}", [WorkoutController::class, 'show']);
    Route::put("/workouts/{id}", [WorkoutController::class, 'update']);
    Route::delete("/workouts/{id}", [WorkoutController::class, 'destroy']);

    Route::get("/workouts/category/{category}", [WorkoutController::class, 'workoutsByCategories']);
    Route::get("/workouts/{id}/exercises", [WorkoutController::class, 'allExercisesByWorkoutId']);
    Route::put("/workouts/category/{id}", [WorkoutController::class, 'changeCategory']);
    Route::put("/workouts/done/{id}", [WorkoutController::class, 'done']);

    Route::get("/exercises", [ExercisesController::class, 'index']);
    Route::get("/exercises/{id}", [ExercisesController::class, 'show']);
    Route::post("/exercises", [ExercisesController::class, 'store']);
    Route::put("/exercises/{id}", [ExercisesController::class, 'update']);
    Route::delete("/exercises/{id}", [ExercisesController::class, 'destroy']);

    Route::get("/types", [TypeController::class, 'index']);
    Route::get("/types/{id}", [TypeController::class, 'show']);
    Route::post("/types", [TypeController::class, 'store']);
    Route::put("/types/{id}", [TypeController::class, 'update']);
    Route::delete("/types/{id}", [TypeController::class, 'destroy']);

    Route::get("/categories", [CategoryController::class, 'index']);
    Route::get("/categories/{id}", [CategoryController::class, 'show']);
    Route::post("/categories", [CategoryController::class, 'store']);
    Route::put("/categories/{id}", [CategoryController::class, 'update']);
    Route::delete("/categories/{id}", [CategoryController::class, 'destroy']);
});
