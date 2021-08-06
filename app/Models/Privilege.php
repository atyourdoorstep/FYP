<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    use HasFactory;
    protected $fillable = [
        'privilege_name',
    ];
    public function rolePrivileges()
    {
        return $this->hasMany(RolePrivilege::class);
    }
}
