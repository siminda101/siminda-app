<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomInventory extends Model
{
    protected $fillable = [
        'room_id',
        'item_id',
        'acquisition_year',
        'quantity_good',
        'quantity_minor',
        'quantity_severe',
        'mutation_notes',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function mutations(): HasMany
    {
        return $this->hasMany(Mutation::class);
    }
}
