<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
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

    // ========== ACCESSORS ==========

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->email === 'anonymous@donation.com') {
            return asset('profile_photos/anonymous-avatar.jpg');
        }

        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            return Storage::url($this->profile_photo);
        }

        return $this->getDefaultAvatar();
    }

    public function getDefaultAvatar()
    {
        $name = $this->name ?? 'User';
        $initial = strtoupper(substr($name, 0, 1));
        $colors = ['#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', '#EF4444', '#14B8A6', '#F97316'];
        $color = $colors[abs(crc32($name)) % count($colors)];

        return 'data:image/svg+xml;base64,'.base64_encode("
            <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'>
                <rect width='100' height='100' rx='50' fill='{$color}'/>
                <text x='50' y='62' font-family='Arial' font-size='40' fill='white' text-anchor='middle' font-weight='bold'>
                    {$initial}
                </text>
            </svg>
        ");
    }

    public function getDisplayNameAttribute()
    {
        if ($this->email === 'anonymous@donation.com') {
            return '🤫 Anonymous Donor';
        }

        return $this->name;
    }

    public function getIsAnonymousAttribute()
    {
        return $this->email === 'anonymous@donation.com';
    }

    // ========== SCOPES ==========

    public function scopeRealDonors($query)
    {
        return $query->where('email', '!=', 'anonymous@donation.com');
    }

    public function scopeAnonymous($query)
    {
        return $query->where('email', 'anonymous@donation.com');
    }

    // ========== STATIC METHODS ==========

    public static function getAnonymousDonor()
    {
        return self::firstOrCreate(
            ['email' => 'anonymous@donation.com'],
            [
                'name' => 'Anonymous Donor',
                'password' => bcrypt('anonymous@123'),
                'phone' => 'N/A',
                'address' => 'N/A',
                'profile_photo' => null,
            ]
        );
    }

    // Add these methods to your User model

    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => now(),
        ])->save();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }
}
