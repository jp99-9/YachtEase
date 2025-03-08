<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Boat extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['name', 'house', 'size', 'incorporation_date', 'password', 'unique_code'];

    protected $hidden = ['password', 'remember_token'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_boat_roles')->withPivot('role_id', 'status', 'start_date', 'end_date')->withTimestamps();
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
