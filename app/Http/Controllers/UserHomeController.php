<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Cloth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserHomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's location
        $userLatitude = null;
        $userLongitude = null;

        if ($request->session()->has('user_latitude') && $request->session()->has('user_longitude')) {
            $userLatitude = $request->session()->get('user_latitude');
            $userLongitude = $request->session()->get('user_longitude');
        } elseif ($user->latitude && $user->longitude) {
            $userLatitude = $user->latitude;
            $userLongitude = $user->longitude;
        } else {
            $userLatitude = 27.7172;
            $userLongitude = 85.3240;
        }

        // Get nearby admins
        $nearbyAdmins = $this->getNearbyAdmins($userLatitude, $userLongitude);

        // Get selected admin from session
        $selectedAdminId = $request->session()->get('selected_admin_id');
        $selectedAdmin = null;
        $clothes = collect();

        if ($selectedAdminId) {
            $selectedAdmin = Admin::find($selectedAdminId);
            if ($selectedAdmin) {
                // Remove is_available check - only check quantity > 0
                $clothes = Cloth::where('admin_id', $selectedAdminId)
                    ->where('quantity', '>', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        // Get user stats
        $totalDonated = 0;
        $totalRequests = 0;
        $livesImpacted = 0;

        return view('user.home', compact(
            'nearbyAdmins',
            'selectedAdmin',
            'clothes',
            'totalDonated',
            'totalRequests',
            'livesImpacted',
            'userLatitude',
            'userLongitude'
        ));
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Store in session
        $request->session()->put('user_latitude', $request->latitude);
        $request->session()->put('user_longitude', $request->longitude);

        // Optionally save to user's profile
        $user = Auth::user();
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function selectAdmin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
        ]);

        $request->session()->put('selected_admin_id', $request->admin_id);

        return redirect()->route('user.home')->with('success', 'Collection center selected successfully');
    }

    public function clearAdmin(Request $request)
    {
        $request->session()->forget('selected_admin_id');

        return redirect()->route('user.home')->with('success', 'Collection center cleared');
    }

    private function getNearbyAdmins($latitude, $longitude, $radius = 50)
    {
        // Remove is_active check
        $admins = Admin::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        foreach ($admins as $admin) {
            $admin->distance = $this->calculateDistance(
                $latitude, $longitude,
                $admin->latitude, $admin->longitude
            );
        }

        // Filter by radius and sort by distance
        $admins = $admins->filter(function ($admin) use ($radius) {
            return $admin->distance <= $radius;
        })->sortBy('distance');

        // If no admins found within radius, get all with distance
        if ($admins->isEmpty()) {
            $admins = Admin::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();

            foreach ($admins as $admin) {
                $admin->distance = $this->calculateDistance(
                    $latitude, $longitude,
                    $admin->latitude, $admin->longitude
                );
            }

            $admins = $admins->sortBy('distance');
        }

        return $admins;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    private function getLocationFromIP()
    {
        try {
            // Get client IP address
            $ip = request()->ip();

            // Skip local IPs
            if ($ip == '127.0.0.1' || $ip == '::1') {
                return null;
            }

            // Use free IP API (you can replace with paid service)
            $response = Http::get("http://ip-api.com/json/{$ip}");

            if ($response->successful() && $response['status'] == 'success') {
                return [
                    'lat' => $response['lat'],
                    'lng' => $response['lon'],
                ];
            }
        } catch (\Exception $e) {
            // Fallback to default
            return null;
        }

        return null;
    }
}
