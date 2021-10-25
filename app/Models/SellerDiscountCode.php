<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerDiscountCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'seller_id',
        'item_id',
        'code',
        'quantity',
        'discount',
    ];
    public  function user()
    {
        return $this->belongsTo(User::class);
    }
    public  function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public  function item()
    {
        return $this->belongsTo(Item::class);
    }
}