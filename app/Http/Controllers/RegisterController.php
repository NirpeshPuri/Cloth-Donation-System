<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Show registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // Validate the request (matches your User model fields)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|size:10|unique:users,phone', // Make sure 'unique:users,phone' is here
            'age' => 'nullable|integer|min:18|max:120',
            'gender' => 'nullable|in:male,female,other',
            // 'user_type' => 'required|in:donor,receiver',
            'password' => 'required|min:8|confirmed',
            'address' => 'required|string|max:255',
        ]);

        // Create user with your model structure
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'age' => $validated['age'] ?? null,
            'gender' => $validated['gender'] ?? null,
            // 'role' => $validated['user_type'], // 'donor' or 'receiver'
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'],
            // 'latitude' and 'longitude' can be added later from address geocoding
        ]);

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }
}
