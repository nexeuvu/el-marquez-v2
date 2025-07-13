<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room_service extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'room_id',
        'service_type',
        'service_date',
        'observations',
    ];

    protected $casts = [
        'service_date' => 'date',
    ];

    /**
     * Relación con Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id");
    }

    /**
     * Relación con Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, "room_id");
    }
}
