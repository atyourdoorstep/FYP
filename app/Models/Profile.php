<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id' ,
        'title' ,
        'url' ,
        'description',
        'image',
        ];
    public function profileImage()
    {
        return $this->image??'';
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
