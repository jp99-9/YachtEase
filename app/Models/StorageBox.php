<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StorageBox extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'capacity', 'location_id'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
