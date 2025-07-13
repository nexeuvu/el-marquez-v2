<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'room_id',
        'unit_price',
        'number_night',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'number_night' => 'integer',
    ];

    /**
     * Relación con Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Relación con Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
