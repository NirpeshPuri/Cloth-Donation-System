<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EsewaKhalti extends Model
{
    protected $table = 'esewa_khaltis';

    protected $fillable = [
        'user_id',
        'amount',
        'transaction_id',
        'payment_status',
    ];

    const STATUS_COMPLETED = 'completed';

    const STATUS_FAILED = 'failed';

    // Relationship (optional but good)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
