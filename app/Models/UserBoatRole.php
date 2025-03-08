<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBoatRole extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'boat_id', 'role_id', 'status', 'start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boat()
    {
        return $this->belongsTo(Boat::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
