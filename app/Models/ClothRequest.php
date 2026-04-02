<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothRequest extends Model
{
    protected $table = 'requests';

    protected $fillable = [
        'receiver_id',
        'cloth_id',
        'quantity',
        'status',
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function cloth()
    {
        return $this->belongsTo(Cloth::class);
    }
}
