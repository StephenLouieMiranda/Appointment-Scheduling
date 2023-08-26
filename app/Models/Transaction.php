<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $appends = [
        'chart_date'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_code', 'patient_code');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_code', 'service_code');
    }

    public function scopeAmount($query, $amount = null)
    {
        return $query->where('amount' , 'LIKE', "%{$amount}%");
    }

    public function getChartDateAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d');
    }

    public function scopeDates($query, array $dates)
    {
        if(empty($dates)) return;

        return $query->whereBetween('created_at', $dates);
    }
}
