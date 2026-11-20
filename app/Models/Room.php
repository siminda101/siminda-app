<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'code',
        'name',
        'province',
        'city',
        'work_unit',
        'work_section',
        'description',
        'is_active',
    ];

    public function inventories()
    {
        return $this->hasMany(RoomInventory::class, 'room_id');
    }
}
