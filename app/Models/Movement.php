<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'date', 'reason', 'observations', 'profile_id', 'location_id', 'item_id'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
