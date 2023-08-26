<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'fullname',
        'pob',
        'dob',
        'phone',
        'address',
        'image',
        'is_active',
        'created_by',
        'updated_by',
    ];
}