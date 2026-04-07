<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Donation;
use App\Models\DonationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

  // ADD THIS LINE

class DonationController extends Controller
{
    public function create(Request $request)
    {
        try {
            Log::info('=== DONATION CREATE METHOD STARTED ===');

            // Get user's location
            $userLatitude = null;
            $userLongitude = null;

            if ($request->session()->has('user_latitude') && $request->session()->has('user_longitude')) {
                $userLatitude = $request->session()->get('user_latitude');
                $userLongitude = $request->session()->get('user_longitude');
                Log::info('Location from session', ['lat' => $userLatitude, 'lng' => $userLongitude]);
            } elseif (Auth::user()->latitude && Auth::user()->longitude) {
                $userLatitude = Auth::user()->latitude;
                $userLongitude = Auth::user()->longitude;
                Log::info('Location from user profile', ['lat' => $userLatitude, 'lng' => $userLongitude]);
            } else {
                $userLatitude = 27.7172;
                $userLongitude = 85.3240;
                Log::info('Using default location (Kathmandu)', ['lat' => $userLatitude, 'lng' => $userLongitude]);
            }

            // Get all admins
            Log::info('Fetching admins...');
            $admins = Admin::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();

            Log::info('Admins found', ['count' => $admins->count()]);

            // Calculate distance for each admin
            foreach ($admins as $admin) {
                $admin->distance = $this->calculateDistance(
                    $userLatitude, $userLongitude,
                    $admin->latitude, $admin->longitude
                );
                Log::info('Admin distance calculated', [
                    'admin' => $admin->name,
                    'distance' => $admin->distance,
                ]);
            }

            // Sort by distance
            $admins = $admins->sortBy('distance');

            Log::info('=== DONATION CREATE METHOD COMPLETED ===');

            return view('user.donate', compact('admins', 'userLatitude', 'userLongitude'));

        } catch (\Exception $e) {
            Log::error('ERROR in create(): '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return back()->with('error', 'Error loading form: '.$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('=== DONATION STORE METHOD STARTED ===');
            Log::info('Request data:', $request->all());

            // Check if user is logged in
            if (! Auth::check()) {
                Log::error('User not authenticated');

                return redirect()->route('login')->with('error', 'Please login first');
            }

            Log::info('User authenticated', ['user_id' => Auth::id(), 'user_name' => Auth::user()->name]);

            // Validate admin_id
            Log::info('Validating admin_id...');
            if (! $request->has('admin_id')) {
                Log::error('admin_id not found in request');

                return back()->with('error', 'Please select a collection center');
            }

            $adminExists = Admin::where('id', $request->admin_id)->exists();
            if (! $adminExists) {
                Log::error('Admin not found', ['admin_id' => $request->admin_id]);

                return back()->with('error', 'Selected collection center does not exist');
            }

            Log::info('Admin validation passed', ['admin_id' => $request->admin_id]);

            // Validate items
            if (! $request->has('items') || empty($request->items)) {
                Log::error('No items found in request');

                return back()->with('error', 'Please add at least one clothing item');
            }

            Log::info('Items count', ['count' => count($request->items)]);

            // Validate each item
            foreach ($request->items as $index => $item) {
                if (empty($item['cloth_name'])) {
                    Log::error("Item {$index} missing cloth_name");

                    return back()->with('error', 'Item '.($index + 1).' is missing cloth name');
                }
                if (empty($item['quantity']) || $item['quantity'] < 1) {
                    Log::error("Item {$index} invalid quantity");

                    return back()->with('error', 'Item '.($index + 1).' has invalid quantity');
                }
                Log::info("Item {$index} validated", ['name' => $item['cloth_name'], 'qty' => $item['quantity']]);
            }

            DB::beginTransaction();
            Log::info('Database transaction started');

            // Create donation record
            Log::info('Creating donation record...');
            $donationData = [
                'donor_id' => Auth::id(),
                'admin_id' => $request->admin_id,
                'status' => 'pending',
                'date_of_donation' => now(),
                'donation_type' => 'multiple',
            ];

            // Add optional fields if they exist in the table
            if (Schema::hasColumn('donations', 'pickup_address')) {
                $donationData['pickup_address'] = $request->pickup_address ?? null;
            }
            if (Schema::hasColumn('donations', 'notes')) {
                $donationData['notes'] = $request->notes ?? null;
            }

            Log::info('Donation data:', $donationData);

            $donation = Donation::create($donationData);
            Log::info('Donation created', ['donation_id' => $donation->id]);

            // Create donation items
            foreach ($request->items as $index => $item) {
                Log::info("Creating donation item {$index}...");

                $itemData = [
                    'donation_id' => $donation->id,
                    'cloth_name' => $item['cloth_name'],
                    'cloth_type' => $item['cloth_type'] ?? null,
                    'gender' => $item['gender'] ?? null,
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'quantity' => $item['quantity'],
                    'quality' => $item['quality'] ?? null,
                    'description' => $item['description'] ?? null,
                ];

                // Handle image if exists
                if ($request->hasFile("items.{$index}.image")) {
                    $imagePath = $request->file("items.{$index}.image")->store('donation_items', 'public');
                    $itemData['image_path'] = $imagePath;
                    Log::info("Image uploaded for item {$index}", ['path' => $imagePath]);
                }

                DonationItem::create($itemData);
                Log::info("Donation item {$index} created");
            }

            DB::commit();
            Log::info('Transaction committed successfully');
            Log::info('=== DONATION STORE METHOD COMPLETED ===');

            return redirect()->route('user.donate')->with('success', 'Thank you for your donation! Our team will review it.');

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: '.json_encode($e->errors()));

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== ERROR IN STORE METHOD ===');
            Log::error('Error message: '.$e->getMessage());
            Log::error('Error code: '.$e->getCode());
            Log::error('Error file: '.$e->getFile());
            Log::error('Error line: '.$e->getLine());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return back()->with('error', 'Error: '.$e->getMessage())->withInput();
        }
    }

    public function myDonations()
    {
        try {
            Log::info('=== MY DONATIONS METHOD STARTED ===');

            $donations = Donation::where('donor_id', Auth::id())
                ->with('items')
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Donations found', ['count' => $donations->count()]);

            return view('user.my-donations', compact('donations'));

        } catch (\Exception $e) {
            Log::error('Error in myDonations: '.$e->getMessage());

            return back()->with('error', 'Error loading donations: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            Log::info('=== DONATION DETAIL METHOD STARTED ===', ['donation_id' => $id]);

            $donation = Donation::where('donor_id', Auth::id())
                ->with('items')
                ->findOrFail($id);

            return view('user.donation-detail', compact('donation'));

        } catch (\Exception $e) {
            Log::error('Error in show: '.$e->getMessage());

            return back()->with('error', 'Donation not found');
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 1);
    }
}
