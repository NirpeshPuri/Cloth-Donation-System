<?php

namespace App\Services;

use App\Models\Cloth;
use App\Models\ClothRequest;
use App\Models\UserPreference;
use App\Models\UserSearchHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiverRecommendationService
{
    /**
     * Get personalized recommendations for receiver based on their request history and preferences
     */
    public function getPersonalizedRecommendations($adminId = null, $limit = 12)
    {
        $user = Auth::user();

        if (! $user) {
            return collect();
        }

        // Get user preferences from search history (gender, size, quality)
        $preferences = $this->getUserCombinedPreferences($user->id);

        // If user has preferences, use them for recommendations
        if (! empty($preferences['genders']) || ! empty($preferences['sizes']) || ! empty($preferences['qualities'])) {
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

        // If no history or preferences, return popular items
        return $this->getPopularItems($adminId, $limit);
    }

    /**
     * Get user preferences from search history and saved preferences
     */
    private function getUserCombinedPreferences($userId)
    {
        $preferences = [
            'genders' => [],
            'sizes' => [],
            'qualities' => [],
            'categories' => [],
        ];

        // Get saved preferences from database
        try {
            $savedPreferences = UserPreference::where('user_id', $userId)
                ->orderBy('count', 'desc')
                ->orderBy('last_used_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($savedPreferences as $pref) {
                $type = $pref->preference_type;
                $value = $pref->preference_value;

                // Map preference_type to array key
                $mappedType = $type.'s'; // gender -> genders, size -> sizes, quality -> qualities
                if (isset($preferences[$mappedType])) {
                    $preferences[$mappedType][$value] = ($preferences[$mappedType][$value] ?? 0) + $pref->count;
                }
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }

        // Get recent search history
        try {
            $recentSearches = UserSearchHistory::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            foreach ($recentSearches as $search) {
                if ($search->gender) {
                    $preferences['genders'][$search->gender] = ($preferences['genders'][$search->gender] ?? 0) + 3;
                }
                if ($search->size) {
                    $preferences['sizes'][$search->size] = ($preferences['sizes'][$search->size] ?? 0) + 2;
                }
                if ($search->quality) {
                    $preferences['qualities'][$search->quality] = ($preferences['qualities'][$search->quality] ?? 0) + 2;
                }
                if ($search->category) {
                    $preferences['categories'][$search->category] = ($preferences['categories'][$search->category] ?? 0) + 3;
                }
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }

        // Sort by count and get top preferences
        foreach ($preferences as $key => $value) {
            arsort($preferences[$key]);
            $preferences[$key] = array_slice($preferences[$key], 0, 3, true);
        }

        return $preferences;
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

        // Sort by frequency and get top preferences
        foreach ($preferences as $key => $value) {
            arsort($preferences[$key]);
            $preferences[$key] = array_slice($preferences[$key], 0, 3, true);
        }

        return $preferences;
    }

    /**
     * Get recommendations based on receiver's preferences
     */
    private function getRecommendationsByPreferences($preferences, $adminId, $limit)
    {
        $query = Cloth::where('quantity', '>', 0)
            ->where('status', 'available');

        if ($adminId) {
            $query->where('admin_id', $adminId);
        }

        // Apply preference filters by priority
        $hasPreference = false;

        // Priority 1: Match category (highest weight)
        if (! empty($preferences['categories'])) {
            $topCategory = array_key_first($preferences['categories']);
            if ($topCategory) {
                $query->where('category', 'like', '%'.$topCategory.'%');
                $hasPreference = true;
            }
        }

        // Priority 2: Match gender (highest weight)
        if (! empty($preferences['genders'])) {
            $topGender = array_key_first($preferences['genders']);
            if ($topGender) {
                $query->where('gender', $topGender);
                $hasPreference = true;
            }
        }

        // Priority 3: Match size (medium weight)
        if (! empty($preferences['sizes'])) {
            $topSize = array_key_first($preferences['sizes']);
            if ($topSize) {
                $query->where('size', $topSize);
            }
        }

        // Priority 4: Match quality (lowest weight)
        if (! empty($preferences['qualities'])) {
            $topQuality = array_key_first($preferences['qualities']);
            if ($topQuality) {
                $query->where('quality', $topQuality);
            }
        }

        $recommendations = $query->limit($limit)->get();

        // If no recommendations with all filters, try with just gender
        if ($recommendations->isEmpty() && $hasPreference) {
            $query = Cloth::where('quantity', '>', 0)
                ->where('status', 'available');

            if ($adminId) {
                $query->where('admin_id', $adminId);
            }

            if (! empty($preferences['categories'])) {
                $topGender = array_key_first($preferences['categories']);
                $query->where('category', 'like', '%'.$topCategory.'%');
            }

            $recommendations = $query->limit($limit)->get();
        }

        return $recommendations;
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

        $popularItems = $query->groupBy('clothes.id', 'clothes.name', 'clothes.category', 'clothes.gender', 'clothes.size', 'clothes.color', 'clothes.image_path', 'clothes.quantity', 'clothes.quality', 'clothes.description', 'clothes.status', 'clothes.admin_id', 'clothes.donor_id', 'clothes.brand_id', 'clothes.cloth_type_id', 'clothes.created_at', 'clothes.updated_at', 'clothes.deleted_at')
            ->orderBy(DB::raw('COUNT(requests.id)'), 'desc')
            ->limit($limit)
            ->get();

        // If no popular items, get recent items
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
        // Find other receivers who requested this cloth
        $receiversWhoRequested = ClothRequest::where('cloth_id', $clothId)
            ->pluck('receiver_id');

        if ($receiversWhoRequested->isEmpty()) {
            return collect();
        }

        // Find other clothes requested by these receivers
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
