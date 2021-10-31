<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'type',
        'category_id',
        'seller_id',
        'inStock',
        'isBargainAble',
        ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function seller()
    {
       return $this->belongsTo(Seller::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
