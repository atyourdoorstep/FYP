<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function invoiceItems()
    {
//        return $this->hasMany()
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
