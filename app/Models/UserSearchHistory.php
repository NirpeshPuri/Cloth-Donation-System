<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSearchHistory extends Model
{
    protected $table = 'user_search_history';

    protected $fillable = [
        'user_id',
        'search_term',
        'gender',
        'size',
        'quality',
        'category',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function saveSearch($userId, $data)
    {
        return self::create([
            'user_id' => $userId,
            'search_term' => $data['search'] ?? null,
            'gender' => $data['gender'] ?? null,
            'size' => $data['size'] ?? null,
            'quality' => $data['quality'] ?? null,
            'category' => $data['category'] ?? null,
        ]);
    }

    public static function getRecentSearches($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->unique('search_term')
            ->values();
    }
}
