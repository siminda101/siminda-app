<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    protected $fillable = [
        'room_inventory_id',
        'from_room_id',
        'to_room_id',
        'mutation_date',
        'quantity_good',
        'quantity_minor',
        'quantity_severe',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'mutation_date' => 'date',
    ];

    public function roomInventory()
    {
        return $this->belongsTo(RoomInventory::class);
    }

    public function fromRoom()
    {
        return $this->belongsTo(Room::class, 'from_room_id');
    }

    public function toRoom()
    {
        return $this->belongsTo(Room::class, 'to_room_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
