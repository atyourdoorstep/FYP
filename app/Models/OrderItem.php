<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'order_id',
        'seller_id',
        'quantity',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function payment()
    {
        return $this->hasOne(PaymentOrderItem::class);
    }
}
