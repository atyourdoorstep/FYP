<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
    public function wallet()
    {
        return $this->hasOne(SellerWallet::class);
    }
//    public static function getSellerRatingAvg($id)
//    {
////        $a=$this->items;
////        $a=Arr::pluck($this->items, 'id');
//        $temp=Arr::pluck(Review::whereIn('item_id',Arr::pluck(Seller::find($id)->items, 'id'))->get(),'rating');
//        return array_sum($temp)/count($temp);
//    }
    public function getSellerRatingAvg()
    {
        $temp=Arr::pluck(Review::whereIn('item_id',Arr::pluck($this->items, 'id'))->get(),'rating');
        if(!count($temp))
            return 0;
        return array_sum($temp)/count($temp);
    }
}
