<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email already verified.');
        }

        $user->markEmailAsVerified();

        return redirect()->route('login')->with('success', 'Email verified successfully! You can now login.');
    }

    public function resend(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email already verified.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent to your email!');
    }
}
