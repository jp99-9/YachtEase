<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email'];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function boats()
    {
        return $this->belongsToMany(Boat::class, 'user_boat_roles')
                    ->withPivot('role_id', 'status', 'start_date', 'end_date')
                    ->withTimestamps();
    }
}