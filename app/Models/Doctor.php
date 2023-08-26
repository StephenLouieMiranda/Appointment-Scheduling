<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Doctor extends Authenticatable
{
    use HasFactory, HasRoles, HasApiTokens, Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $appends = [
        'clinic_schedule'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_code', 'user_code');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctor_id');
    }

    public function getClinicScheduleAttribute()
    {
        $days = [];
        foreach ($this->schedules as $key => $schedule) {
            $days[$key] = $schedule->day;
        }
        return array_unique($days);
    }
}
