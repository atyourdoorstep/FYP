<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public function seller()
    {
        return $this->hasMany(Seller::class);
    }
    public function children()
    {
        return $this->hasMany(Category::class);
    }
    public function parent()
    {
        return $this->belongsTo(Category::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
