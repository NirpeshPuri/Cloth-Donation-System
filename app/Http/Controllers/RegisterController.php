<?php

// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;

// class RegisterController extends Controller
// {
//     // Show registration form
//     public function showRegistrationForm()
//     {
//         return view('auth.register');
//     }

//     // Handle registration
//     public function register(Request $request)
//     {
//         // Validate the request (matches your User model fields)
//         $validated = $request->validate([
//             'name' => 'required|string|max:20',
//             'email' => 'required|email|unique:users,email',
//             'phone' => 'required|string|size:10|unique:users,phone', // Make sure 'unique:users,phone' is here
//             'age' => 'nullable|integer|min:16|max:120',
//             'gender' => 'nullable|in:male,female,other',
//             // 'user_type' => 'required|in:donor,receiver',
//             'password' => 'required|min:8|confirmed',
//             'address' => 'required|string|max:255',
//             'profile_photo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048', // Correct image validation
//             'profile_photo_base64' => 'nullable|string', // Base64 string validation
//         ]);

//         // Handle profile photo - priority: base64 > file upload
//         $profilePhotoPath = null;

//         // First check if we have base64 image (from cropper)
//         if ($request->filled('profile_photo_base64')) {
//             $profilePhotoPath = $this->saveBase64Image($request->profile_photo_base64);
//         }
//         // If no base64 but has file upload
//         elseif ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
//             $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
//         }

//         // Create user with your model structure
//         $user = User::create([
//             'name' => $validated['name'],
//             'email' => $validated['email'],
//             'phone' => $validated['phone'],
//             'age' => $validated['age'] ?? null,
//             'gender' => $validated['gender'] ?? null,
//             // 'role' => $validated['user_type'], // 'donor' or 'receiver'
//             'password' => Hash::make($validated['password']),
//             'address' => $validated['address'],
//             'profile_photo' => $profilePhotoPath,
//             // 'latitude' and 'longitude' can be added later from address geocoding
//         ]);

//         // Redirect to login with success message
//         return redirect()->route('login')->with('success', 'Registration successful! Please login.');
//     }

//     /**
//      * Save base64 image to storage
//      */
//     private function saveBase64Image($base64Image)
//     {
//         // Extract image data
//         if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
//             $imageType = $matches[1];
//             $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
//             $imageData = base64_decode($imageData);

//             // Generate unique filename
//             $filename = 'profile_'.Str::random(20).'.'.$imageType;
//             $path = 'profile_photos/'.$filename;

//             // Save to storage
//             Storage::disk('public')->put($path, $imageData);

//             return $path;
//         }

//         return null;
//     }
// }

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

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
        // Validate the request with all rules
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/', // Only letters and spaces
            ],
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => [
                'required',
                'string',
                'size:10',
                'regex:/^(98|97)[0-9]{8}$/', // Starts with 98 or 97 and exactly 10 digits
                'unique:users,phone',
            ],
            'age' => 'required|integer|min:16|max:120',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:255',
            'password' => [
                'required',
                'confirmed',
                'min:6',
                Password::min(6)
                    ->mixedCase() // Requires at least one uppercase and one lowercase
                    ->numbers() // Requires at least one number
                    ->symbols(), // Requires at least one special character
            ],
            'profile_photo' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'profile_photo_base64' => 'nullable|string',
        ], [
            // Custom error messages
            'name.regex' => 'Name can only contain letters and spaces.',
            'phone.regex' => 'Phone number must start with 98 or 97 and be exactly 10 digits.',
            'phone.size' => 'Phone number must be exactly 10 digits.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.mixedCase' => 'Password must contain at least one uppercase and one lowercase letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character (!@#$%^&* etc).',
            'age.required' => 'Age is required.',
            'age.min' => 'You must be at least 16 years old.',
            'gender.required' => 'Please select your gender.',
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

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'],
            'profile_photo' => $profilePhotoPath,
            'email_verified_at' => null,
        ]);

        $user->sendEmailVerificationNotification();

        // Redirect to login with success message
        // return redirect()->route('login')->with('success', 'Registration successful! Please login.');

        // CHANGE REDIRECT - go to verification notice instead of login
        return redirect()->route('verification.notice')->with('success', 'Registration successful! Please check your email to verify your account.');
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
