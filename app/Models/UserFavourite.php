<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavourite extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function favouriteCategory()
    {
        return $this->hasMany(FavouriteCategory::class);
    }
}
