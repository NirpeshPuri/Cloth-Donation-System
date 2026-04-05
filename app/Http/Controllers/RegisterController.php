<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'profile_photo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048', // Correct image validation
            'profile_photo_base64' => 'nullable|string', // Base64 string validation
        ]);

        // Handle profile photo - priority: base64 > file upload
        $profilePhotoPath = null;

        // First check if we have base64 image (from cropper)
        if ($request->filled('profile_photo_base64')) {
            $profilePhotoPath = $this->saveBase64Image($request->profile_photo_base64);
        }
        // If no base64 but has file upload
        elseif ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

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
            'profile_photo' => $profilePhotoPath,
            // 'latitude' and 'longitude' can be added later from address geocoding
        ]);

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    /**
     * Save base64 image to storage
     */
    private function saveBase64Image($base64Image)
    {
        // Extract image data
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $imageType = $matches[1];
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($imageData);

            // Generate unique filename
            $filename = 'profile_'.Str::random(20).'.'.$imageType;
            $path = 'profile_photos/'.$filename;

            // Save to storage
            Storage::disk('public')->put($path, $imageData);

            return $path;
        }

        return null;
    }
}
