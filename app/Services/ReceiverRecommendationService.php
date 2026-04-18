<?php

namespace App\Services;

use App\Models\Cloth;
use App\Models\ClothRequest;
use App\Models\UserSearchHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiverRecommendationService
{
    /**
     * Get personalized recommendations for receiver based on their request history and preferences
     */
    public function getPersonalizedRecommendations($adminId = null, $limit = 8)
    {
        $user = Auth::user();

        if (! $user) {
            return collect();
        }

        // Get user preferences from ALL search history (fresh from database)
        $preferences = $this->getUserCombinedPreferences($user->id);

        // If user has preferences, use them for recommendations
        if (! empty($preferences['categories']) || ! empty($preferences['genders']) ||
            ! empty($preferences['sizes']) || ! empty($preferences['qualities'])) {
            $recommendations = $this->getRecommendationsByPreferences($preferences, $adminId, $limit);
            if ($recommendations->isNotEmpty()) {
                return $recommendations;
            }
        }

        // If user has request history, use that
        $userRequests = ClothRequest::where('receiver_id', $user->id)
            ->with('cloth')
            ->whereHas('cloth')
            ->get();

        if ($userRequests->isNotEmpty()) {
            $preferences = $this->extractReceiverPreferences($userRequests);
            $recommendations = $this->getRecommendationsByPreferences($preferences, $adminId, $limit);
            if ($recommendations->isNotEmpty()) {
                return $recommendations;
            }
        }

        // If no history or preferences, return popular items (limited to 8)
        return $this->getPopularItems($adminId, $limit);
    }

    /**
     * Get user preferences from ALL search history (not just recent)
     */
    private function getUserCombinedPreferences($userId)
    {
        $preferences = [
            'genders' => [],
            'sizes' => [],
            'qualities' => [],
            'categories' => [],
        ];

        // Get ALL search history and count frequencies
        try {
            $allSearches = UserSearchHistory::where('user_id', $userId)->get();

            foreach ($allSearches as $search) {
                if ($search->category) {
                    $preferences['categories'][$search->category] = ($preferences['categories'][$search->category] ?? 0) + 1;
                }
                if ($search->gender) {
                    $preferences['genders'][$search->gender] = ($preferences['genders'][$search->gender] ?? 0) + 1;
                }
                if ($search->size) {
                    $preferences['sizes'][$search->size] = ($preferences['sizes'][$search->size] ?? 0) + 1;
                }
                if ($search->quality) {
                    $preferences['qualities'][$search->quality] = ($preferences['qualities'][$search->quality] ?? 0) + 1;
                }
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }

        // Sort by count (highest first)
        foreach ($preferences as $key => $value) {
            arsort($preferences[$key]);
            $preferences[$key] = array_slice($preferences[$key], 0, 5, true);
        }

        return $preferences;
    }

    /**
     * Get recommendations based on receiver's preferences - returns diverse results and changes position
     */
    // private function getRecommendationsByPreferences($preferences, $adminId, $limit)
    // {
    //     $allRecommendations = collect();
    //     $usedClothIds = collect();

    //     // Get top categories sorted by count (highest first)
    //     $topCategories = ! empty($preferences['categories']) ? array_keys($preferences['categories']) : [];
    //     $topGenders = ! empty($preferences['genders']) ? array_keys($preferences['genders']) : [];
    //     $topSizes = ! empty($preferences['sizes']) ? array_keys($preferences['sizes']) : [];

    //     // Calculate total weight for randomization
    //     $totalWeight = array_sum($preferences['categories']) + array_sum($preferences['genders']);

    //     // Level 1: Get items from top categories (weighted by search count)
    //     foreach ($topCategories as $category) {
    //         if ($allRecommendations->count() >= $limit) {
    //             break;
    //         }

    //         $categoryCount = $preferences['categories'][$category] ?? 1;
    //         $itemsToTake = max(1, min(3, ceil($limit * ($categoryCount / $totalWeight))));
    //         $remaining = min($itemsToTake, $limit - $allRecommendations->count());

    //         if ($remaining > 0) {
    //             $query = Cloth::where('admin_id', $adminId)
    //                 ->where('quantity', '>', 0)
    //                 ->where('status', 'available')
    //                 ->where('category', $category);

    //             // Apply gender preference if available
    //             if (! empty($topGenders)) {
    //                 $query->whereIn('gender', $topGenders);
    //             }

    //             $more = $query->whereNotIn('id', $usedClothIds)
    //                 ->inRandomOrder()  // Random order for variety
    //                 ->limit($remaining)
    //                 ->get();

    //             $allRecommendations = $allRecommendations->merge($more);
    //             $usedClothIds = $usedClothIds->merge($more->pluck('id'));
    //         }
    //     }

    //     // Level 2: Add items by gender only (if still need more)
    //     if ($allRecommendations->count() < $limit && ! empty($topGenders)) {
    //         $remaining = $limit - $allRecommendations->count();
    //         $more = Cloth::where('admin_id', $adminId)
    //             ->where('quantity', '>', 0)
    //             ->where('status', 'available')
    //             ->whereIn('gender', $topGenders)
    //             ->whereNotIn('id', $usedClothIds)
    //             ->inRandomOrder()
    //             ->limit($remaining)
    //             ->get();
    //         $allRecommendations = $allRecommendations->merge($more);
    //         $usedClothIds = $usedClothIds->merge($more->pluck('id'));
    //     }

    //     // Level 3: Add recent items (if still need more)
    //     if ($allRecommendations->count() < $limit) {
    //         $remaining = $limit - $allRecommendations->count();
    //         $more = Cloth::where('admin_id', $adminId)
    //             ->where('quantity', '>', 0)
    //             ->where('status', 'available')
    //             ->whereNotIn('id', $usedClothIds)
    //             ->orderBy('created_at', 'desc')
    //             ->limit($remaining)
    //             ->get();
    //         $allRecommendations = $allRecommendations->merge($more);
    //     }

    //     // Shuffle the final collection to change positions
    //     $allRecommendations = $allRecommendations->shuffle();

    //     return $allRecommendations;
    // }

    private function getRecommendationsByPreferences($preferences, $adminId, $limit)
    {
        $allRecommendations = collect();
        $usedClothIds = collect();

        // Get top preferences
        $topCategories = ! empty($preferences['categories']) ? array_keys($preferences['categories']) : [];
        $topGenders = ! empty($preferences['genders']) ? array_keys($preferences['genders']) : [];
        $topSizes = ! empty($preferences['sizes']) ? array_keys($preferences['sizes']) : [];

        // Calculate total weight
        $totalWeight = array_sum($preferences['categories']) + array_sum($preferences['genders']);
        if ($totalWeight == 0) {
            $totalWeight = 1;
        }

        // ===============================
        // LEVEL 1: Category-based items
        // ===============================
        foreach ($topCategories as $category) {
            if ($allRecommendations->count() >= $limit) {
                break;
            }

            $categoryCount = $preferences['categories'][$category] ?? 1;
            $itemsToTake = max(1, min(3, ceil($limit * ($categoryCount / $totalWeight))));
            $remaining = min($itemsToTake, $limit - $allRecommendations->count());

            if ($remaining > 0) {
                $query = Cloth::where('admin_id', $adminId)
                    ->where('quantity', '>', 0)
                    ->where('status', 'available')
                    ->where('category', $category);

                if (! empty($topGenders)) {
                    $query->whereIn('gender', $topGenders);
                }

                $more = $query->whereNotIn('id', $usedClothIds)
                    ->inRandomOrder()
                    ->limit($remaining)
                    ->get();

                $allRecommendations = $allRecommendations->merge($more);
                $usedClothIds = $usedClothIds->merge($more->pluck('id'));
            }
        }

        // ===============================
        // LEVEL 2: Gender-based items
        // ===============================
        if ($allRecommendations->count() < $limit && ! empty($topGenders)) {
            $remaining = $limit - $allRecommendations->count();

            $more = Cloth::where('admin_id', $adminId)
                ->where('quantity', '>', 0)
                ->where('status', 'available')
                ->whereIn('gender', $topGenders)
                ->whereNotIn('id', $usedClothIds)
                ->inRandomOrder()
                ->limit($remaining)
                ->get();

            $allRecommendations = $allRecommendations->merge($more);
            $usedClothIds = $usedClothIds->merge($more->pluck('id'));
        }

        // ===============================
        // LEVEL 3: Recent items fallback
        // ===============================
        if ($allRecommendations->count() < $limit) {
            $remaining = $limit - $allRecommendations->count();

            $more = Cloth::where('admin_id', $adminId)
                ->where('quantity', '>', 0)
                ->where('status', 'available')
                ->whereNotIn('id', $usedClothIds)
                ->orderBy('created_at', 'desc')
                ->limit($remaining)
                ->get();

            $allRecommendations = $allRecommendations->merge($more);
        }

        // ===============================
        // ⭐ COSINE SIMILARITY (INLINE FORMULA)
        // ===============================
        if ($allRecommendations->isNotEmpty()) {

            $userVector = $this->buildUserVector($preferences);

            $allRecommendations = $allRecommendations->map(function ($item) use ($userVector) {

                $itemVector = $this->buildItemVector($item);

                // ---- Cosine Similarity Calculation ----
                $dotProduct = 0;
                $magnitudeA = 0;
                $magnitudeB = 0;

                $keys = array_unique(array_merge(array_keys($userVector), array_keys($itemVector)));

                foreach ($keys as $key) {
                    $a = $userVector[$key] ?? 0;
                    $b = $itemVector[$key] ?? 0;

                    $dotProduct += $a * $b;
                    $magnitudeA += $a * $a;
                    $magnitudeB += $b * $b;
                }

                if ($magnitudeA > 0 && $magnitudeB > 0) {
                    $similarity = $dotProduct / (sqrt($magnitudeA) * sqrt($magnitudeB));
                } else {
                    $similarity = 0;
                }

                $item->similarity_score = $similarity;

                return $item;
            });

            // Sort by similarity score
            $allRecommendations = $allRecommendations
                ->sortByDesc('similarity_score')
                ->values()
                ->shuffle();
        }

        return $allRecommendations;
    }

    private function buildItemVector($cloth)
    {
        $vector = [];

        if ($cloth->category) {
            $vector['cat_'.$cloth->category] = 1;
        }

        if ($cloth->gender) {
            $vector['gender_'.$cloth->gender] = 1;
        }

        if ($cloth->size) {
            $vector['size_'.$cloth->size] = 1;
        }

        if ($cloth->quality) {
            $vector['quality_'.$cloth->quality] = 1;
        }

        return $vector;
    }

    private function buildUserVector($preferences)
    {
        $vector = [];

        foreach ($preferences['categories'] as $key => $value) {
            $vector['cat_'.$key] = $value;
        }

        foreach ($preferences['genders'] as $key => $value) {
            $vector['gender_'.$key] = $value;
        }

        foreach ($preferences['sizes'] as $key => $value) {
            $vector['size_'.$key] = $value;
        }

        foreach ($preferences['qualities'] as $key => $value) {
            $vector['quality_'.$key] = $value;
        }

        return $vector;
    }

    /**
     * Extract receiver's preferences from their request history
     */
    private function extractReceiverPreferences($userRequests)
    {
        $preferences = [
            'genders' => [],
            'sizes' => [],
            'qualities' => [],
            'categories' => [],
        ];

        foreach ($userRequests as $request) {
            $cloth = $request->cloth;
            if ($cloth) {
                if ($cloth->category) {
                    $preferences['categories'][$cloth->category] = ($preferences['categories'][$cloth->category] ?? 0) + $request->quantity;
                }
                if ($cloth->gender) {
                    $preferences['genders'][$cloth->gender] = ($preferences['genders'][$cloth->gender] ?? 0) + $request->quantity;
                }
                if ($cloth->size) {
                    $preferences['sizes'][$cloth->size] = ($preferences['sizes'][$cloth->size] ?? 0) + $request->quantity;
                }
                if ($cloth->quality) {
                    $preferences['qualities'][$cloth->quality] = ($preferences['qualities'][$cloth->quality] ?? 0) + $request->quantity;
                }
            }
        }

        foreach ($preferences as $key => $value) {
            arsort($preferences[$key]);
            $preferences[$key] = array_slice($preferences[$key], 0, 3, true);
        }

        return $preferences;
    }

    /**
     * Get popular items based on overall request counts from all receivers
     */
    public function getPopularItems($adminId = null, $limit = 12)
    {
        $query = Cloth::select('clothes.*')
            ->leftJoin('requests', 'clothes.id', '=', 'requests.cloth_id')
            ->where('clothes.quantity', '>', 0)
            ->where('clothes.status', 'available');

        if ($adminId) {
            $query->where('clothes.admin_id', $adminId);
        }

        $popularItems = $query->groupBy('clothes.id')
            ->orderBy(DB::raw('COUNT(requests.id)'), 'desc')
            ->limit($limit)
            ->get();

        if ($popularItems->isEmpty()) {
            $query = Cloth::where('quantity', '>', 0)
                ->where('status', 'available');

            if ($adminId) {
                $query->where('admin_id', $adminId);
            }

            $popularItems = $query->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        }

        return $popularItems;
    }

    /**
     * Get "Frequently Requested Together" items (Collaborative Filtering)
     */
    public function getFrequentlyRequestedTogether($clothId, $adminId = null, $limit = 6)
    {
        $receiversWhoRequested = ClothRequest::where('cloth_id', $clothId)
            ->pluck('receiver_id');

        if ($receiversWhoRequested->isEmpty()) {
            return collect();
        }

        $relatedClothIds = ClothRequest::whereIn('receiver_id', $receiversWhoRequested)
            ->where('cloth_id', '!=', $clothId)
            ->groupBy('cloth_id')
            ->select('cloth_id', DB::raw('COUNT(*) as request_count'))
            ->orderBy('request_count', 'desc')
            ->limit($limit)
            ->pluck('cloth_id');

        $query = Cloth::whereIn('id', $relatedClothIds)
            ->where('quantity', '>', 0)
            ->where('status', 'available');

        if ($adminId) {
            $query->where('admin_id', $adminId);
        }

        return $query->get();
    }

    /**
     * Get seasonal recommendations
     */
    public function getSeasonalRecommendations($season, $adminId = null, $limit = 8)
    {
        $seasonalCategories = [
            'summer' => ['shirt', 't-shirt', 'tshirt', 'shorts', 'dress'],
            'winter' => ['jacket', 'sweater', 'hoodie', 'coat', 'blazer'],
            'monsoon' => ['jacket', 'raincoat', 'hoodie'],
            'festival' => ['saree', 'kurta', 'traditional', 'ethnic', 'dress'],
        ];

        $categories = $seasonalCategories[$season] ?? ['shirt', 't-shirt', 'dress'];

        $query = Cloth::whereIn('category', $categories)
            ->where('quantity', '>', 0)
            ->where('status', 'available');

        if ($adminId) {
            $query->where('admin_id', $adminId);
        }

        return $query->limit($limit)->get();
    }
}
