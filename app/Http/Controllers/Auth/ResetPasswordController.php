<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:6',
                PasswordRule::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.mixedCase' => 'Password must contain at least one uppercase and one lowercase letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Your password has been reset successfully! Please login with your new password.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}
