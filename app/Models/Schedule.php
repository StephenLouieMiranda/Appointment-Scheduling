<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'day_in_num',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Doctor::class, 'user_code', 'user_code', 'doctor_id', 'id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_code', 'appointment_code');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function getDayAttribute()
    {
        return Carbon::now()->startOfWeek()->addDays($this->attributes['day'] - 1)->dayName;
    }

    public function getDayInNumAttribute()
    {
        return $this->attributes['day'];
    }
}
