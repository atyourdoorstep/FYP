<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerFolder extends Model
{
    use HasFactory;
    protected $fillable = [
        'main',
        'invoice',
        'return_invoice',
        'item',
        'seller_id',
        ];
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
