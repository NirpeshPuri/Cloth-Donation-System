<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cloth;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    // Show admin login form
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Handle admin login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to login as admin
        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Admin logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    // Show admin dashboard
    public function dashboard()
    {
        $totalDonations = Donation::count();
        $pendingDonations = Donation::where('status', 'pending')->count();
        $totalClothes = Cloth::sum('quantity');
        $totalAdmins = Admin::count();

        return view('admin.dashboard', compact(
            'totalDonations',
            'pendingDonations',
            'totalClothes',
            'totalAdmins'
        ));
    }
}
