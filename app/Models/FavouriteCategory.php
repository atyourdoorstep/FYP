<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'user_favourites_id',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function userFavourite()
    {
        return $this->belongsTo(UserFavourite::class);
    }
}
