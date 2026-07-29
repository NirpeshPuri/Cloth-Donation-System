<?php

namespace App\Http\Controllers;

use App\Models\Cloth;
use App\Services\ReceiverRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserPreferenceController extends Controller
{
    protected $recommendationService;

    public function __construct(ReceiverRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
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
