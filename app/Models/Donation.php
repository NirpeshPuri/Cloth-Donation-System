<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donor_id',
        'receiver_id',
        'cloth_id',
        'quantity',
        'status',
        'date_of_donation',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function cloth()
    {
        return $this->belongsTo(Cloth::class);
    }
}
