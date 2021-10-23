<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'stripe_payment_id',
        'type',
        'status',
        'order_id',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
