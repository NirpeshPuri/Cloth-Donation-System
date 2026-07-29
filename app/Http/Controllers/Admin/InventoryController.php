<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cloth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    /**
     * Display inventory list
     */
    public function index(Request $request)
    {
        $adminId = Auth::guard('admin')->id();

        $query = Cloth::where('admin_id', $adminId)->with('donor');

        // Search filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('category', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Gender filter
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        // Donor filter
        if ($request->donor_filter) {
            if ($request->donor_filter === 'anonymous') {
                $query->whereHas('donor', function ($q) {
                    $q->where('email', 'anonymous@donation.com');
                });
            } elseif ($request->donor_filter === 'real_donor') {
                $query->whereHas('donor', function ($q) {
                    $q->where('email', '!=', 'anonymous@donation.com');
                });
            } else {
                $query->where('donor_id', $request->donor_filter);
            }
        }

        $clothes = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $totalItems = Cloth::where('admin_id', $adminId)->sum('quantity');
        $availableItems = Cloth::where('admin_id', $adminId)->where('status', 'available')->sum('quantity');
        $reservedItems = Cloth::where('admin_id', $adminId)->where('status', 'reserved')->sum('quantity');
        $donatedItems = Cloth::where('admin_id', $adminId)->where('status', 'donated')->sum('quantity');
        $lowStockItems = Cloth::where('admin_id', $adminId)->where('quantity', '<=', 5)->where('quantity', '>', 0)->count();

        // Get all donors for filter (excluding anonymous)
        $donors = User::where('email', '!=', 'anonymous@donation.com')
            ->orderBy('name')
            ->get();

        return view('admin.inventory.index', compact(
            'clothes',
            'totalItems',
            'availableItems',
            'reservedItems',
            'donatedItems',
            'lowStockItems',
            'donors'
        ));
    }

    /**
     * Show form to add new cloth
     */
    public function create()
    {
        return view('admin.inventory.create');
    }

    /**
     * Store new cloth in inventory
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'quality' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:available,reserved,donated',
            'donor_id' => 'nullable|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('clothes', 'public');
        }

        // If no donor selected, assign anonymous donor
        $donorId = $validated['donor_id'] ?? null;
        if (! $donorId) {
            $anonymousDonor = Cloth::getAnonymousDonor();
            $donorId = $anonymousDonor->id;
        }

        // Create cloth
        Cloth::create([
            'admin_id' => Auth::guard('admin')->id(),
            'donor_id' => $donorId,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'gender' => $validated['gender'],
            'size' => $validated['size'],
            'color' => $validated['color'],
            'quantity' => $validated['quantity'],
            'quality' => $validated['quality'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Cloth added to inventory successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $cloth = Cloth::where('admin_id', Auth::guard('admin')->id())->findOrFail($id);

        return view('admin.inventory.edit', compact('cloth'));
    }

    /**
     * Update cloth
     */
    public function update(Request $request, $id)
    {
        $cloth = Cloth::where('admin_id', Auth::guard('admin')->id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'quality' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:available,reserved,donated',
            'donor_id' => 'nullable|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($cloth->image_path) {
                Storage::disk('public')->delete($cloth->image_path);
            }
            $imagePath = $request->file('image')->store('clothes', 'public');
            $validated['image_path'] = $imagePath;
        }

        // If no donor selected, assign anonymous donor
        $donorId = $validated['donor_id'] ?? null;
        if (! $donorId) {
            $anonymousDonor = Cloth::getAnonymousDonor();
            $donorId = $anonymousDonor->id;
        }
        $validated['donor_id'] = $donorId;

        $cloth->update($validated);

        return redirect()->route('admin.inventory.index')->with('success', 'Item updated successfully!');
    }

    /**
     * Delete cloth
     */
    public function destroy($id)
    {
        $cloth = Cloth::where('admin_id', Auth::guard('admin')->id())->findOrFail($id);

        if ($cloth->image_path) {
            Storage::disk('public')->delete($cloth->image_path);
        }

        $cloth->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Item removed from inventory.');
    }

    /**
     * Show low stock items
     */
    public function lowStock()
    {
        $adminId = Auth::guard('admin')->id();
        $clothes = Cloth::where('admin_id', $adminId)
            ->where('quantity', '<=', 5)
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'asc')
            ->get();

        return view('admin.inventory.low-stock', compact('clothes'));
    }

    /**
     * Bulk status update
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:clothes,id',
            'status' => 'required|in:available,reserved,donated',
        ]);

        $adminId = Auth::guard('admin')->id();

        Cloth::where('admin_id', $adminId)
            ->whereIn('id', $request->ids)
            ->update(['status' => $request->status]);

        return redirect()->route('admin.inventory.index')->with('success', 'Bulk status updated successfully!');
    }

    /**
     * Search donor for select2 (exclude anonymous donor)
     */
    public function searchDonor(Request $request)
    {
        try {
            $query = $request->get('q', '');

            if (empty($query) || strlen($query) < 1) {
                return response()->json([]);
            }

            $donors = User::where('email', '!=', 'anonymous@donation.com')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%'.$query.'%')
                        ->orWhere('email', 'like', '%'.$query.'%')
                        ->orWhere('phone', 'like', '%'.$query.'%');
                })
                ->limit(10)
                ->get(['id', 'name', 'email', 'phone', 'profile_photo']);

            $results = $donors->map(function ($donor) {
                $phone = $donor->phone ?? 'No phone';

                return [
                    'id' => $donor->id,
                    'text' => $donor->name.' - '.$donor->email.' ('.$phone.')',
                ];
            });

            return response()->json($results);

        } catch (\Exception $e) {
            \Log::error('Donor search error: '.$e->getMessage());

            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    /**
     * Get donor details for display
     */
    public function getDonor($id)
    {
        try {
            $donor = User::find($id);
            if (! $donor) {
                return response()->json(null);
            }

            return response()->json([
                'id' => $donor->id,
                'name' => $donor->name,
                'email' => $donor->email,
                'phone' => $donor->phone,
                'profile_photo' => $donor->profile_photo_url,
                'is_anonymous' => $donor->email === 'anonymous@donation.com',
            ]);
        } catch (\Exception $e) {
            return response()->json(null);
        }
    }
}
