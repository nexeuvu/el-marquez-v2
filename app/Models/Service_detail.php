<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'employee_id',
        'booking_id',
        'quantity',
        'consumption_date',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'consumption_date' => 'date',
        'total' => 'float',
    ];

    /**
     * Relación con Service
     */
    public function service()
    { 
        return $this->belongsTo(Service::class); 
    }

    /**
     * Relación con Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relación con Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
