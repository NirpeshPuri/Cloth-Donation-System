<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|unique:users,phone,'.$user->id,
            'age' => 'nullable|integer|min:18|max:120',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'required|string|max:255',

            'profile_photo_base64' => 'nullable|string',

            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // PASSWORD CHANGE
        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Current password is incorrect',
                ]);
            }

            $user->password = Hash::make($request->new_password);
        }

        // PROFILE IMAGE (CROPPED BASE64)
        if ($request->filled('profile_photo_base64')) {

            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->profile_photo = $this->saveBase64Image($request->profile_photo_base64);
        }

        // UPDATE BASIC INFO
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (! \Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect',
            ]);
        }

        $user->update([
            'password' => \Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    // LOCATION UPDATE (GPS)
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $user = Auth::user();
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        return response()->json(['success' => true]);
    }

    // BASE64 IMAGE SAVE (REGISTER STYLE)
    private function saveBase64Image($base64Image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {

            $imageType = $matches[1];
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($imageData);

            $filename = 'profile_'.Str::random(20).'.'.$imageType;
            $path = 'profile_photos/'.$filename;

            Storage::disk('public')->put($path, $imageData);

            return $path;
        }

        return null;
    }
}
