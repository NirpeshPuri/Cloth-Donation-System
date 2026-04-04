<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 👉 Import related models

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'age',
        'gender',
        'profile_photo',
        'latitude',
        'longitude',
        // 'role',
        'address',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 🔥 Relationships

    public function clothes()
    {
        return $this->hasMany(Cloth::class, 'donor_id');
    }

    public function requests()
    {
        return $this->hasMany(ClothRequest::class, 'receiver_id');
    }

    public function donationsGiven()
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    public function donationsReceived()
    {
        return $this->hasMany(Donation::class, 'receiver_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
