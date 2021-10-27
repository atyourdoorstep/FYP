<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCodeItem extends Model
{
    use HasFactory;
    public function discount()
    {
        return $this->belongsTo(DiscountCode::class);
    }
    public  function item()
    {
        return $this->belongsTo(Item::class);
    }
}
