<?php

namespace App\Http\Controllers;

use App\Models\Exercises;
use App\Models\Types;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $types = Types::whereNull('deleted_at')
                ->get();

            if ($types->isEmpty()) {
                return response()->json(['error' => 'No types found'], 404);
            }

            return response()->json(['data' => $types], 201);
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
            $type = Types::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();
            return response()->json(['data' => $type], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Type not found'], 500);
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
            ]);

            $type = Types::create([
                'title' => $validated['title'],
            ]);

            return response()->json(['data' => $type], 201);
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
            $type = Types::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $validated = $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $type->update($validated);

            return response()->json(['data' => $type], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Type not found'], 404);
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
            $type = Types::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $type->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json([], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Type not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
