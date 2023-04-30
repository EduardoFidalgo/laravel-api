<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exercises;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::whereNull('deleted_at')
                ->get();

            if ($categories->isEmpty()) {
                return response()->json(['error' => 'No categories found'], 404);
            }

            return response()->json(['data' => $categories], 201);
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
            $category = Category::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            return response()->json(['data' => $category], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Category not found'], 500);
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

            $category = Category::create([
                'title' => $validated['title'],
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
            $category = Category::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
            ]);

            $category->update($validated);

            return response()->json(['data' => $category], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
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
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
