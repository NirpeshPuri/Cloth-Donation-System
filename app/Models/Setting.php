<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    public static function getCategories()
    {
        $setting = self::where('key', 'categories')->first();

        return $setting ? $setting->value : [];
    }

    public static function updateCategories($categories)
    {
        $setting = self::where('key', 'categories')->first();
        if ($setting) {
            $setting->value = $categories;
            $setting->save();
        }
    }
}
