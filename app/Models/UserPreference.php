<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $table = 'user_preferences';

    protected $fillable = [
        'user_id',
        'preference_type',
        'preference_value',
        'count',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update or create user preference
     */
    public static function updatePreference($userId, $type, $value)
    {
        if (! $value || $value == '') {
            return null;
        }

        $preference = self::where('user_id', $userId)
            ->where('preference_type', $type)
            ->where('preference_value', $value)
            ->first();

        if ($preference) {
            // Increment count and update timestamp
            $preference->increment('count');
            $preference->last_used_at = now();
            $preference->save();
        } else {
            // Create new preference
            self::create([
                'user_id' => $userId,
                'preference_type' => $type,
                'preference_value' => $value,
                'count' => 1,
                'last_used_at' => now(),
            ]);
        }

        return true;
    }

    /**
     * Get user's top preferences by type
     */
    public static function getUserPreferences($userId, $limit = 5)
    {
        return self::where('user_id', $userId)
            ->orderBy('count', 'desc')
            ->orderBy('last_used_at', 'desc')
            ->limit($limit)
            ->get()
            ->groupBy('preference_type');
    }
}
