<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'avatar', 'status', 'user_id', 'boat_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boat()
    {
        return $this->belongsTo(Boat::class);
    }

    public function sessions()
    {
        return $this->hasMany(ProfileSession::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
