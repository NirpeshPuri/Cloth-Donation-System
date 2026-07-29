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

    // ADD THESE NEW ACCESSORS
    public function getDonorNameAttribute()
    {
        return $this->donor ? $this->donor->name : 'No Donor';
    }

    public function getReceiverNameAttribute()
    {
        return $this->receiver ? $this->receiver->name : 'No Receiver';
    }

    public function getClothNameAttribute()
    {
        return $this->cloth ? $this->cloth->name : 'Unknown Cloth';
    }

    public function getFormattedDateAttribute()
    {
        return $this->date_of_donation ? $this->date_of_donation->format('M d, Y') : 'N/A';
    }

    // ADD THESE SCOPES
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date_of_donation', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date_of_donation', now()->month)
            ->whereYear('date_of_donation', now()->year);
    }

    // ADD THESE HELPER METHODS
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    public function canBeApproved()
    {
        return $this->status === 'pending';
    }

    public function canBeCompleted()
    {
        return $this->status === 'approved' || $this->status === 'processing';
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? 'Cancelled: '.$reason."\n".($this->notes ?? '') : ($this->notes ?? ''),
        ]);
    }

    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'notes' => $reason ? 'Rejected: '.$reason."\n".($this->notes ?? '') : ($this->notes ?? ''),
        ]);
    }

    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function complete()
    {
        $this->update(['status' => 'completed']);
    }

    public function process()
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Get or create the anonymous donor
     */
    public static function getAnonymousDonor()
    {
        return User::getAnonymousDonor();
    }
}
