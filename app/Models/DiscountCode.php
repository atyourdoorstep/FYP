<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'seller_id',
        'code',
    ];
    public  function user()
    {
        return $this->belongsTo(User::class);
    }
    public  function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function discountCodeItems()
    {
        return $this->hasMany(DiscountCodeItem::class);
    }
}
