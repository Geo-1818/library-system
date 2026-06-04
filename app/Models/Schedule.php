<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'service_id',
        'available_date',
        'start_time',
        'end_time',
        'available_slots',
    ];

    protected $casts = [
        'available_date' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
