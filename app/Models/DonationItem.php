<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DonationItem extends Model
{
    protected $table = 'donation_items';

    protected $fillable = [
        'donation_id',
        'cloth_name',
        'cloth_type',
        'gender',
        'size',
        'color',
        'quantity',
        'quality',
        'description',
        'image_path',
    ];

    // Relationships
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }

        return null;
    }

    public function getQualityLabelAttribute()
    {
        $qualities = [
            'new' => 'New (With tags)',
            'like_new' => 'Like New',
            'good' => 'Good',
            'fair' => 'Fair',
        ];

        return $qualities[$this->quality] ?? $this->quality;
    }

    public function getGenderLabelAttribute()
    {
        $genders = [
            'men' => '👨 Men',
            'women' => '👩 Women',
            'kids' => '🧒 Kids',
            'unisex' => '👥 Unisex',
        ];

        return $genders[$this->gender] ?? $this->gender;
    }
}
