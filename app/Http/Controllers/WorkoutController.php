<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exercises;
use App\Models\Types;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $workouts = Workout::whereNull('deleted_at')
                ->get();

            foreach ($workouts as $wo) {
                $categoryTitle = Category::where('id', $wo->category)
                    ->where('deleted_at', null)
                    ->get();
                $wo['category_data'] = $categoryTitle;
            }

            if ($workouts->isEmpty()) {
                return response()->json(['error' => 'No workouts found'], 404);
            }

            return response()->json(['data' => $workouts], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function workoutsByCategories($category_id)
    {
        try {
            $workouts = Workout::where('category', $category_id)
                ->where('deleted_at', null)
                ->get();

            foreach ($workouts as $wo) {
                $categoryTitle = Category::where('id', $wo->category)
                    ->where('deleted_at', null)
                    ->get();
                $wo['category_data'] = $categoryTitle;
            }

            if ($workouts->isEmpty()) {
                return response()->json(['error' => 'No workouts found'], 404);
            }

            return response()->json(['data' => $workouts], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(INT $id)
    {
        try {
            $workout = Workout::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $categoryTitle = Category::where('id', $workout->category)
                ->where('deleted_at', null)
                ->get();
            $workout['category_data'] = $categoryTitle;

            return response()->json(['data' => $workout], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'workout not found'], 500);
        }
    }

    public function allExercisesByWorkoutId(INT $workout_id)
    {
        try {
            $exercises = Exercises::where('workout_id', $workout_id)
                ->where('deleted_at', null)
                ->get();

            foreach ($exercises as $exercise) {
                $typeData = Types::select('title', 'created_at', 'updated_at', 'deleted_at')
                    ->where('id', $exercise->type)
                    ->where('deleted_at', null)
                    ->get();
                $exercise['typeData'] = $typeData;
            }

            return response()->json(['data' => $exercises], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'workout not found'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'category' => 'required',
            ]);

            $workout = Workout::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
            ]);

            return response()->json(['data' => $workout], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, INT $id)
    {
        try {
            $workout = Workout::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'category' => 'nullable',
            ]);

            $workout->update($validated);

            return response()->json(['data' => $workout], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Workout not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function changeCategory(Request $request, INT $id)
    {
        try {
            $workout = Workout::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $validated = $request->validate([
                'category' => 'required',
            ]);

            $workout->update($validated);

            $categoryTitle = Category::select('title', 'created_at', 'updated_at', 'deleted_at')
                ->where('id', $workout->category)
                ->where('deleted_at', null)
                ->get();
            $workout['category_data'] = $categoryTitle;

            return response()->json(['data' => $workout], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Workout not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function done(Request $request, INT $id)
    {
        try {
            $workout = Workout::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $workout->update([
                'done_at' => Carbon::now(),
            ]);

            $categoryTitle = Category::select('title', 'created_at', 'updated_at', 'deleted_at')
                ->where('id', $workout->category)
                ->where('deleted_at', null)
                ->get();
            $workout['category_data'] = $categoryTitle;

            return response()->json(['data' => $workout], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Workout not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(INT $id)
    {
        try {
            $workout = Workout::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $workout->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json([], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Workout not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
