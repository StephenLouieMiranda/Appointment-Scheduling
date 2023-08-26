<?php

namespace App\Models;

use App\Models\Scopes\AppointmentScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes, AppointmentScopes;

    protected $guarded = [];

    protected $appends = [
        'is_available_now',
        'schedule',
        'schedule_status',
        'status_description',
        'chart_date'
    ];

    public function scopeToday($q, $value)
    {
        if(empty($value)) return;
        $q->whereDate('date', Carbon::today());
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_code', 'user_code');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'patient_code', 'patient_code');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_code', 'schedule_code');
    }

    public function getFullnameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getScheduleAttribute()
    {
        return Carbon::parse($this->attributes['date'])->format('M d, Y,') . " " .
            Carbon::parse($this->attributes['time'])->format('h:i A');
    }

    public function getIsAvailableNowAttribute()
    {
        if(empty($this->date))
        {
            return false;
        }
        else
        {
            list($hours, $minutes, $seconds) = explode(':', $this->time);
            return Carbon::parse($this->date)->addHours($hours)->addMinutes($minutes)->diffInMinutes(now()) <= 1;
        }
    }

    public function getScheduleStatusAttribute()
    {
        if(empty($this->date))
        {
            return "Not Available";
        }
        else
        {
            list($hours, $minutes, $seconds) = explode(':', $this->time);
            $schedule = Carbon::parse($this->date)->addHours($hours)->addMinutes($minutes);
            $now = Carbon::parse($this->date)->addHours($hours)->addMinutes($minutes)->diffInMinutes(now()) >= 0 && $schedule->diffInMinutes(now()) <= 4;
            $missed = Carbon::parse($this->date)->addHours($hours)->addMinutes($minutes)->diffInMinutes(now()) >= 5;

            if(($schedule->isFuture()) && $this->status == 'approved')
            {
                return "Upcoming";
            }
            else if($this->status == 'cancelled')
            {
                return "Cancelled";
            }
            else if(($schedule->isFuture() || $schedule->isPast()) && $this->status == 'pending')
            {
                return "Pending";
            }
            else if($missed && $this->status == 'approved')
            {
                return "Missed";
            }
            else if(($now || $schedule->diffInMinutes(now()) >= 4) && $this->status == 'approved')
            {
                return "Available";
            }
            else if($schedule->isPast() && $this->status == 'done')
            {
                return "Over";
            }
        }
    }

    public function getStatusDescriptionAttribute()
    {

        switch (true) {
            case 'pending':
                return "Pending for doctor approval.";
                break;
            case 'approved':
                return "Approved. Awaits Schedule";
                break;
            case 'missed':
                return "Missed";
                break;
            case 'Done':
                return "DONE";
                break;
            default:
                return "Not Available";
                break;
        }
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = date('Y-m-d', strtotime($value));
    }

    public function getChartDateAttribute()
    {
        return Carbon::parse($this->attributes['date'])->format('Y-m-d');
    }
}
