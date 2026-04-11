<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cloth extends Model
{
    use SoftDeletes;

    protected $table = 'clothes';

    protected $fillable = [
        'name',
        'category',
        'gender',
        'admin_id',
        'donor_id',
        'brand_id',
        'cloth_type_id',
        'size',
        'color',
        'image_path',
        'quantity',
        'quality',
        'description',
        'status',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function clothType()
    {
        return $this->belongsTo(ClothType::class);
    }

    public function requests()
    {
        return $this->hasMany(ClothRequest::class, 'cloth_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function isAvailable()
    {
        return $this->quantity > 0;
    }
}
