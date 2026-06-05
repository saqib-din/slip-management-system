<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{
    protected $fillable = [
        'slip_no',
        'date',
        'site_no',
        'time',
        'lpo_no',
        'vehicle_no',
        'company',
        'tip',
        'cash_trip',
        'refund',
        'items',
        'receiver_name',
        'driver',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'date'      => 'datetime',
        'tip'       => 'decimal:2',
        'cash_trip' => 'decimal:2',
        'items'     => 'array',
    ];
}
