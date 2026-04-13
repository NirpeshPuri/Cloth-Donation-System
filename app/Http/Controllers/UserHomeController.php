<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Cloth;
use App\Models\ClothRequest;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Services\ReceiverRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserHomeController extends Controller
{
    protected $recommendationService;

    public function __construct(ReceiverRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's location
        $userLatitude = null;
        $userLongitude = null;
        $currentSeason = $this->getCurrentSeason();

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

        // Clothes available for receiving
        $availableClothes = collect();
        $recommendedClothes = collect();
        $seasonalRecommendations = collect();
        $popularItems = collect();
        $categoryGroups = [];

        if ($selectedAdminId) {
            $selectedAdmin = Admin::find($selectedAdminId);
            if ($selectedAdmin) {
                // Get available clothes from this collection center
                $availableClothes = Cloth::where('admin_id', $selectedAdminId)
                    ->where('quantity', '>', 0)
                    ->where('status', 'available')
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Get personalized recommendations based on receiver's request history
                $recommendedClothes = $this->recommendationService->getPersonalizedRecommendations($selectedAdminId, 8);

                // Get seasonal recommendations
                $seasonalRecommendations = $this->recommendationService->getSeasonalRecommendations($currentSeason, $selectedAdminId, 4);

                // Get popular items
                $popularItems = $this->recommendationService->getPopularItems($selectedAdminId, 4);

                // Group clothes by category
                $categoryGroups = $this->groupClothesByCategory($availableClothes);
            }
        }

        // Receiver's activity stats
        $myRequests = ClothRequest::where('receiver_id', $user->id)
            ->with('cloth')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingRequests = $myRequests->where('status', 'pending')->count();
        $approvedRequests = $myRequests->where('status', 'approved')->count();
        $completedRequests = $myRequests->where('status', 'completed')->count();
        $rejectedRequests = $myRequests->where('status', 'rejected')->count();
        $cancelledRequests = $myRequests->where('status', 'cancelled')->count();

        $totalRequestedItems = $myRequests->sum('quantity');

        // My Donations (if user also donates - optional)
        $myDonations = Donation::where('donor_id', $user->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalDonatedItems = DonationItem::whereHas('donation', function ($query) use ($user) {
            $query->where('donor_id', $user->id);
        })->sum('quantity');

        return view('user.home', compact(
            'nearbyAdmins',
            'selectedAdmin',
            'availableClothes',
            'recommendedClothes',
            'seasonalRecommendations',
            'popularItems',
            'categoryGroups',
            'myRequests',
            'pendingRequests',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',
            'cancelledRequests',
            'totalRequestedItems',
            'myDonations',
            'totalDonatedItems',
            'userLatitude',
            'userLongitude',
            'currentSeason'
        ));
    }

    public function clothDetail($id)
    {
        $cloth = Cloth::with('admin')->findOrFail($id);

        // Get frequently requested together items
        $frequentlyRequestedTogether = $this->recommendationService->getFrequentlyRequestedTogether($id, $cloth->admin_id, 4);

        // Get related clothes from same admin
        $relatedClothes = Cloth::where('admin_id', $cloth->admin_id)
            ->where('id', '!=', $id)
            ->where('quantity', '>', 0)
            ->limit(4)
            ->get();

        return view('user.cloth-detail', compact('cloth', 'relatedClothes', 'frequentlyRequestedTogether'));
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $request->session()->put('user_latitude', $request->latitude);
        $request->session()->put('user_longitude', $request->longitude);

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

    private function getCurrentSeason()
    {
        $month = date('n');
        if ($month >= 3 && $month <= 5) {
            return 'summer';
        } elseif ($month >= 6 && $month <= 8) {
            return 'monsoon';
        } elseif ($month >= 9 && $month <= 10) {
            return 'festival';
        } else {
            return 'winter';
        }
    }

    private function groupClothesByCategory($clothes)
    {
        return [
            'shirts' => $clothes->filter(function ($cloth) {
                return in_array(strtolower($cloth->category), ['shirt', 't-shirt', 'tshirt', 'blouse', 'top']);
            }),
            'pants' => $clothes->filter(function ($cloth) {
                return in_array(strtolower($cloth->category), ['jeans', 'pants', 'trousers', 'leggings', 'shorts']);
            }),
            'traditional' => $clothes->filter(function ($cloth) {
                return in_array(strtolower($cloth->category), ['saree', 'kurta', 'dhoti', 'lungi', 'traditional', 'ethnic']);
            }),
            'winter' => $clothes->filter(function ($cloth) {
                return in_array(strtolower($cloth->category), ['jacket', 'sweater', 'hoodie', 'coat', 'blazer']);
            }),
            'dresses' => $clothes->filter(function ($cloth) {
                return in_array(strtolower($cloth->category), ['dress', 'frock', 'gown', 'jumpsuit']);
            }),
            'other' => $clothes->filter(function ($cloth) {
                return ! in_array(strtolower($cloth->category), [
                    'shirt', 't-shirt', 'tshirt', 'blouse', 'top',
                    'jeans', 'pants', 'trousers', 'leggings', 'shorts',
                    'saree', 'kurta', 'dhoti', 'lungi', 'traditional', 'ethnic',
                    'jacket', 'sweater', 'hoodie', 'coat', 'blazer',
                    'dress', 'frock', 'gown', 'jumpsuit',
                ]);
            }),
        ];
    }

    private function getNearbyAdmins($latitude, $longitude, $radius = 50)
    {
        $admins = Admin::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        foreach ($admins as $admin) {
            $admin->distance = $this->calculateDistance(
                $latitude, $longitude,
                $admin->latitude, $admin->longitude
            );
        }

        $admins = $admins->filter(function ($admin) use ($radius) {
            return $admin->distance <= $radius;
        })->sortBy('distance');

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
        $earthRadius = 6371;

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    public function categoryPage($type)
    {
        $user = Auth::user();
        $selectedAdminId = session()->get('selected_admin_id');
        $selectedAdmin = Admin::find($selectedAdminId);

        if (! $selectedAdmin) {
            return redirect()->route('user.home')->with('error', 'Please select a collection center first');
        }

        $clothes = Cloth::where('admin_id', $selectedAdminId)
            ->where('quantity', '>', 0)
            ->where('status', 'available');

        // Filter by category type
        switch ($type) {
            case 'shirts':
                $clothes->whereIn('category', ['shirt', 't-shirt', 'tshirt', 'blouse', 'top']);
                $title = 'Shirts & Tops';
                break;
            case 'pants':
                $clothes->whereIn('category', ['jeans', 'pants', 'trousers', 'leggings', 'shorts']);
                $title = 'Pants & Jeans';
                break;
            case 'traditional':
                $clothes->whereIn('category', ['saree', 'kurta', 'dhoti', 'lungi', 'traditional', 'ethnic']);
                $title = 'Traditional Attire';
                break;
            case 'winter':
                $clothes->whereIn('category', ['jacket', 'sweater', 'hoodie', 'coat', 'blazer']);
                $title = 'Winter Wear';
                break;
            case 'dresses':
                $clothes->whereIn('category', ['dress', 'frock', 'gown', 'jumpsuit']);
                $title = 'Dresses & Gowns';
                break;
            case 'popular':
                $clothes = $this->recommendationService->getPopularItems($selectedAdminId, 100);
                $title = 'Most Popular Items';
                break;
            case 'recommended':
                $clothes = $this->recommendationService->getPersonalizedRecommendations($selectedAdminId, 100);
                $title = 'Recommended For You';
                break;
            case 'seasonal':
                $season = $this->getCurrentSeason();
                $clothes = $this->recommendationService->getSeasonalRecommendations($season, $selectedAdminId, 100);
                $title = ucfirst($season).' Collection';
                break;
            default:
                $clothes = $clothes->get();
                $title = 'All Items';
        }

        if (! in_array($type, ['popular', 'recommended', 'seasonal'])) {
            $clothes = $clothes->get();
        }

        return view('user.category', compact('clothes', 'title', 'selectedAdmin', 'type'));
    }

    public function search(Request $request)
    {
        try {
            $selectedAdminId = session()->get('selected_admin_id');

            if (! $selectedAdminId) {
                return response()->json(['items' => [], 'total' => 0]);
            }

            // Check if any filter is applied
            $hasSearchTerm = $request->search && strlen($request->search) >= 2;
            $hasGender = $request->gender && $request->gender != '';
            $hasSize = $request->size && $request->size != '';
            $hasQuality = $request->quality && $request->quality != '';
            $hasCategory = $request->category && $request->category != '';

            // Save to search history if ANY filter is applied (including filters without search term)
            if ($hasSearchTerm || $hasGender || $hasSize || $hasQuality || $hasCategory) {
                try {
                    DB::table('user_search_history')->insert([
                        'user_id' => Auth::id(),
                        'search_term' => $request->search ?? null,
                        'gender' => $request->gender ?? null,
                        'size' => $request->size ?? null,
                        'quality' => $request->quality ?? null,
                        'category' => $request->category ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    // Table might not exist yet, silently fail
                }
            }

            // Save preferences (try-catch to prevent errors)
            if ($hasGender) {
                try {
                    $existing = DB::table('user_preferences')
                        ->where('user_id', Auth::id())
                        ->where('preference_type', 'gender')
                        ->where('preference_value', $request->gender)
                        ->first();

                    if ($existing) {
                        DB::table('user_preferences')
                            ->where('id', $existing->id)
                            ->update([
                                'count' => $existing->count + 1,
                                'last_used_at' => now(),
                                'updated_at' => now(),
                            ]);
                    } else {
                        DB::table('user_preferences')->insert([
                            'user_id' => Auth::id(),
                            'preference_type' => 'gender',
                            'preference_value' => $request->gender,
                            'count' => 1,
                            'last_used_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    // Table might not exist yet, silently fail
                }
            }

            if ($hasSize) {
                try {
                    $existing = DB::table('user_preferences')
                        ->where('user_id', Auth::id())
                        ->where('preference_type', 'size')
                        ->where('preference_value', $request->size)
                        ->first();

                    if ($existing) {
                        DB::table('user_preferences')
                            ->where('id', $existing->id)
                            ->update([
                                'count' => $existing->count + 1,
                                'last_used_at' => now(),
                                'updated_at' => now(),
                            ]);
                    } else {
                        DB::table('user_preferences')->insert([
                            'user_id' => Auth::id(),
                            'preference_type' => 'size',
                            'preference_value' => $request->size,
                            'count' => 1,
                            'last_used_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    // Silently fail
                }
            }

            if ($hasQuality) {
                try {
                    $existing = DB::table('user_preferences')
                        ->where('user_id', Auth::id())
                        ->where('preference_type', 'quality')
                        ->where('preference_value', $request->quality)
                        ->first();

                    if ($existing) {
                        DB::table('user_preferences')
                            ->where('id', $existing->id)
                            ->update([
                                'count' => $existing->count + 1,
                                'last_used_at' => now(),
                                'updated_at' => now(),
                            ]);
                    } else {
                        DB::table('user_preferences')->insert([
                            'user_id' => Auth::id(),
                            'preference_type' => 'quality',
                            'preference_value' => $request->quality,
                            'count' => 1,
                            'last_used_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    // Silently fail
                }
            }

            if ($hasCategory) {
                try {
                    $existing = DB::table('user_preferences')
                        ->where('user_id', Auth::id())
                        ->where('preference_type', 'category')
                        ->where('preference_value', $request->category)
                        ->first();

                    if ($existing) {
                        DB::table('user_preferences')
                            ->where('id', $existing->id)
                            ->update([
                                'count' => $existing->count + 1,
                                'last_used_at' => now(),
                                'updated_at' => now(),
                            ]);
                    } else {
                        DB::table('user_preferences')->insert([
                            'user_id' => Auth::id(),
                            'preference_type' => 'category',
                            'preference_value' => $request->category,
                            'count' => 1,
                            'last_used_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    // Silently fail
                }
            }

            // Build the search query
            $query = Cloth::where('admin_id', $selectedAdminId)
                ->where('quantity', '>', 0)
                ->where('status', 'available');

            if ($hasSearchTerm) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('description', 'like', '%'.$request->search.'%');
                });
            }

            if ($hasGender) {
                $query->where('gender', $request->gender);
            }

            if ($hasSize) {
                $query->where('size', $request->size);
            }

            if ($hasQuality) {
                $query->where('quality', $request->quality);
            }

            if ($hasCategory) {
                $query->where('category', $request->category);
            }

            // ========== FIXED SORTING - Apply BEFORE get() ==========
            $sortBy = $request->sort_by ?? 'latest';

            if ($sortBy === 'most_requested') {
                $query->withCount('requests')->orderBy('requests_count', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Now execute the query
            $clothes = $query->get();

            return response()->json([
                'items' => $clothes,
                'total' => $clothes->count(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Search error: '.$e->getMessage());

            return response()->json([
                'items' => [],
                'total' => 0,
            ]);
        }
    }

    public function getRecentSearches(Request $request)
    {
        try {
            $searches = DB::table('user_search_history')
                ->where('user_id', Auth::id())
                ->whereNotNull('search_term')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->unique('search_term')
                ->pluck('search_term')
                ->values();

            return response()->json(['searches' => $searches]);
        } catch (\Exception $e) {
            return response()->json(['searches' => []]);
        }
    }

    public function refreshRecommendations()
    {
        // Clear any cached recommendations
        cache()->forget('recommendations_'.Auth::id());

        return response()->json(['success' => true]);
    }
}
