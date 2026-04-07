<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donor_id',
        'receiver_id',
        'admin_id',
        'cloth_id',
        'quantity',
        'status',
        'donation_type',
        'date_of_donation',
        'pickup_address',
        'notes',
    ];

    protected $casts = [
        'date_of_donation' => 'date',
    ];

    // Relationships
    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function cloth()
    {
        return $this->belongsTo(Cloth::class);
    }

    public function items()
    {
        return $this->hasMany(DonationItem::class);
    }

    // Accessors
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => '⏳ Pending',
            'approved' => '✅ Approved',
            'processing' => '🔄 Processing',
            'completed' => '🎉 Completed',
            'rejected' => '❌ Rejected',
            'cancelled' => '🚫 Cancelled',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'approved' => 'blue',
            'processing' => 'purple',
            'completed' => 'green',
            'rejected' => 'red',
            'cancelled' => 'gray',
        ];

        return $colors[$this->status] ?? 'gray';
    }
}
