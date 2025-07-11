<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'dni',
        'name',
        'last_name',     
        'phone',
        'email',
    ];

}
