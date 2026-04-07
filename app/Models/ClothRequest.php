<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothRequest extends Model
{
    protected $table = 'requests';

    protected $fillable = [
        'receiver_id',
        'cloth_id',
        'admin_id',      // ADD THIS - needed to track which collection center
        'quantity',
        'status',
        'notes',         // ADD THIS - for additional notes
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function cloth()
    {
        return $this->belongsTo(Cloth::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
