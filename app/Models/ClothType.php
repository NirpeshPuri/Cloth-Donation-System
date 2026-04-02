<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothType extends Model
{
    protected $fillable = ['name'];

    public function clothes()
    {
        return $this->hasMany(Cloth::class);
    }
}
