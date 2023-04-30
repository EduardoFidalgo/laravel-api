<?php

namespace App\Http\Controllers;

use App\Models\Exercises;
use App\Models\Types;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExercisesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $exercises = Exercises::whereNull('deleted_at')
                ->get();

            if ($exercises->isEmpty()) {
                return response()->json(['error' => 'No users found'], 404);
            }

            foreach ($exercises as $ex) {
                $typeData = Types::select('title', 'created_at', 'updated_at', 'deleted_at')
                    ->where('id', $ex->type)
                    ->where('deleted_at', null)
                    ->get();
                $ex['type_data'] = $typeData;
            }

            return response()->json(['data' => $exercises], 201);
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
            $exercise = Exercises::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $typeData = Types::select(
                'title',
                'created_at',
                'updated_at',
                'deleted_at'
            )->where('id', $exercise->type)
                ->where('deleted_at', null)
                ->firstOrFail();

            $exercise['type_data'] = $typeData;

            return response()->json(['data' => $exercise], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Exercise not found'], 500);
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
                'type' => 'required|integer',
                'series' => 'required|integer',
                'repetitions' => 'required|integer',
                'workout_id' => 'required|integer',
            ]);

            $category = Exercises::create([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'series' => $validated['series'],
                'repetitions' => $validated['repetitions'],
                'workout_id' => $validated['workout_id'],
            ]);

            return response()->json(['data' => $category], 201);
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
            $exercise = Exercises::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|integer',
                'series' => 'required|integer',
                'repetitions' => 'required|integer',
                'workout_id' => 'required|integer',
            ]);

            $exercise->update($validated);

            return response()->json(['data' => $exercise], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Exercise not found'], 404);
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
            $exercise = Exercises::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $exercise->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json([], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Exercise not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
