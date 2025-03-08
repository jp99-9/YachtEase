<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'quantity', 'image', 'minimum_recommended', 'qr_code', 'type_id', 'location_id', 'storage_box_id'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function storageBox()
    {
        return $this->belongsTo(StorageBox::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
