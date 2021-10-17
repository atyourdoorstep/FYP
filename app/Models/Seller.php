<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_name',
        'user_id',
        'category_id'
    ];

    public function sellerFolder()
    {
        return $this->hasOne(SellerFolder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function sellerAddress()
    {
        return $this->hasOne(SellerAddress::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
