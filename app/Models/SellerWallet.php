<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerWallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id',
        'amount',
    ];
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
