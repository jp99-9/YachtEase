<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'device_info',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
        'started_at',
        'ended_at',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
