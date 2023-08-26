<?php

namespace App\Models;

use App\Models\Scopes\MedicalRecordScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MedicalRecord extends Model
{
    use HasFactory, MedicalRecordScopes;

    protected $guarded = [];

    protected $appends = [
        'date_issued',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_code', 'patient_code');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Doctor::class, 'user_code', 'user_code', 'doctor_id', 'id');
    }

    public function getDateIssuedAttribute()
    {
        return Carbon::parse($this->attributes['date_issued'])->format('M d, Y');
    }
}
