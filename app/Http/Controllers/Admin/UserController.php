<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClothRequest;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display list of all users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        // Gender filter
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        // Stats
        $totalUsers = User::count();
        $totalDonors = User::whereHas('donationsGiven')->count();
        $totalReceivers = User::whereHas('requests')->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalDonors',
            'totalReceivers',
            'newUsersThisMonth'
        ));
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::with(['donationsGiven', 'requests'])->findOrFail($id);

        // Get user stats
        $totalDonations = Donation::where('donor_id', $user->id)->count();
        $totalRequests = ClothRequest::where('receiver_id', $user->id)->count();
        $pendingRequests = ClothRequest::where('receiver_id', $user->id)->where('status', 'pending')->count();
        $approvedRequests = ClothRequest::where('receiver_id', $user->id)->where('status', 'approved')->count();
        $completedRequests = ClothRequest::where('receiver_id', $user->id)->where('status', 'completed')->count();
        $rejectedRequests = ClothRequest::where('receiver_id', $user->id)->where('status', 'rejected')->count();

        // Get recent activities
        $recentDonations = Donation::where('donor_id', $user->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentRequests = ClothRequest::where('receiver_id', $user->id)
            ->with('cloth')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'totalDonations',
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',
            'recentDonations',
            'recentRequests'
        ));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:18|max:120',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user data
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'age' => $validated['age'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Check if user has any active donations or requests
        $hasActiveDonations = Donation::where('donor_id', $user->id)->where('status', 'pending')->exists();
        $hasActiveRequests = ClothRequest::where('receiver_id', $user->id)->where('status', 'pending')->exists();

        if ($hasActiveDonations || $hasActiveRequests) {
            return back()->with('error', 'Cannot delete user with active donations or requests.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * Show user's donation history
     */
    public function donations($id)
    {
        $user = User::findOrFail($id);
        $donations = Donation::where('donor_id', $user->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.donations', compact('user', 'donations'));
    }

    /**
     * Show user's request history
     */
    public function requests($id)
    {
        $user = User::findOrFail($id);
        $requests = ClothRequest::where('receiver_id', $user->id)
            ->with('cloth')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.requests', compact('user', 'requests'));
    }

    public function export(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $filename = 'users_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $columns = [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Age',
            'Gender',
            'Address',
            'Email Verified',
            'Latitude',
            'Longitude',
            'Created At',
            'Updated At',
        ];

        $callback = function () use ($users, $columns) {

            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $columns);

            foreach ($users as $user) {

                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->age,
                    $user->gender,
                    $user->address,
                    $user->email_verified_at,
                    $user->latitude,
                    $user->longitude,
                    $user->created_at,
                    $user->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
