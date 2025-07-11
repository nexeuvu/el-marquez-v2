<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'room_type',
        'status',
        'price',
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'float',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_type_id'); // o 'type_id', depende del nombre de tu columna FK
    }
}
