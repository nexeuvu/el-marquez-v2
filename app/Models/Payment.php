<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'booking_id',
        'service_id',
        'room_id',
        'payment_date',
        'total_amount',
        'payment_method',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'total_amount' => 'float',
    ];

    /**
     * Relación con Guest
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    /**
     * Relación con Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Relación con Service (Room_service o Service)
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Relación con Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
