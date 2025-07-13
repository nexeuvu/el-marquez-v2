<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'description',
    'price',
    'status', // importante
];

protected $casts = [
    'name' => 'array',
    'price' => 'float',
    'status' => 'boolean',
];

}
