<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'dni',
        'name',
        'last_name',
        'role',
        'shift',
        'status', // opcional, si manejas activo/inactivo
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
