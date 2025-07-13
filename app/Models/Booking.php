<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'room_id',
        'start_date',
        'end_date',
        'price_pay',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_pay' => 'float',
    ];

    /**
     * Relationship with Guest
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    /**
     * Relationship with Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // 🔧 Agrega esta relación para corregir el error
    public function bookingDetails()
    {
        return $this->hasMany(Booking_detail::class);
    }
}
