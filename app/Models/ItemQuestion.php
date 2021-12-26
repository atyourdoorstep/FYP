<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
        'is_public',
        'item_id',
        'user_id',
        'item_questions_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function parentQuestion()
    {
        return $this->belongsTo(ItemQuestion::class,'item_questions_id','id');
    }
    public function childQuestions()
    {
        return $this->hasMany(ItemQuestion::class,'item_questions_id','id');
    }
}
