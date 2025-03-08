<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'latitude', 'longitude', 'boat_id'];

    public function boat()
    {
        return $this->belongsTo(Boat::class);
    }

    public function storageBoxes()
    {
        return $this->hasMany(StorageBox::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
