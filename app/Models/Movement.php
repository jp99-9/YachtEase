<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'movement_date', 'reason', 'observations', 'profile_id', 'location_id', 'item_id','from_location_id', 'from_box_id', 'to_location_id',
        'to_box_id',];

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
    // Ubicaciones origen y destino
    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    // Cajas origen y destino (pueden ser null)
    public function fromBox()
    {
        return $this->belongsTo(StorageBox::class, 'from_box_id');
    }

    public function toBox()
    {
        return $this->belongsTo(StorageBox::class, 'to_box_id');
    }
}
