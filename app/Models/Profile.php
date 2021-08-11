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
        $size=40;
        $imagePath = ($this->image) ? '/storage/' .$this->image : 'https://www.pngfind.com/pngs/m/676-6764065_default-profile-picture-transparent-hd-png-download.png';

        //return $imagePath;
//      uploads/profilePictures/EfSpuhJyPxLKhWlw823agqPnCKnYzETrUoW8yXFH.jpg
        return Image::make( public_path($imagePath))->response();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
