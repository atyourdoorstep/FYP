<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCodeItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'discount',
        'quantity',
        'discount_code_id',
    ];
    public function discount()
    {
        return $this->belongsTo(DiscountCode::class);
    }
    public  function item()
    {
        return $this->belongsTo(Item::class);
    }
}
