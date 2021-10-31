<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'item_id',
        'review',
        'review_id',
        'rating',
        ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
    public function reply()
    {
        return $this->hasMany(Review::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
