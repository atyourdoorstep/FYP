<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePrivilege extends Model
{
    use HasFactory;
    public function roles()
    {
        return $this->hasMany(Role::class);
    }
    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

}
