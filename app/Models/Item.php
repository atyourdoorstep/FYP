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
        'category_id',
        'seller_id',
        ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function seller()
    {
       return $this->belongsTo(Seller::class);
    }
}
