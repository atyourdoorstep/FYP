<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        ];
    use HasFactory;
    public function seller()
    {
        return $this->hasMany(Seller::class);
    }
    public function children()
    {
        return $this->hasMany(Category::class)->with('children');
    }
//    public function grandChild()
//    {
//        return $this->hasMany(Category::class);
//    }
    public function category()
    {
        return $this->belongsTo(self::class)->with('category');
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
