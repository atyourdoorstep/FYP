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
        if(!$this->image )
            return $this->image;
        $imagePath ='/storage/' .$this->image;


//      uploads/profilePictures/EfSpuhJyPxLKhWlw823agqPnCKnYzETrUoW8yXFH.jpg
        return Image::make( public_path($imagePath))->response();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
