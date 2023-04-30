<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::whereNull('deleted_at')
                ->get();

            if ($users->isEmpty()) {
                return response()->json(['error' => 'No users found'], 404);
            }

            return response()->json(['data' => $users], 201);
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
            $user = User::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            return response()->json(['data' => $user], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'User not found'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json(['data' => $user], 201);
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
            $user = User::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|unique:users,email,' . $user->id . '|max:255',
            ]);

            $user->update($validated);

            return response()->json(['data' => $user], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
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
            $user = User::where('id', $id)
                ->where('deleted_at', null)
                ->firstOrFail();

            $user->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json([], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
