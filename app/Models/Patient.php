<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Patient extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $guard = 'patient';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'fullname',
        'age',
        'address',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = implode(', ', Arr::flatten($value));
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'patient_code', 'patient_code');
    }

    public function medical_history()
    {
        return $this->hasMany(MedicalHistory::class, 'patient_code', 'patient_code');
    }

    public function clinical_notes()
    {
        return $this->hasMany(ClinicalNote::class, 'patient_code', 'patient_code');
    }

    public function laboratory_tests()
    {
        return $this->hasMany(LaboratoryTest::class, 'patient_code', 'patient_code');
    }

    public function diagnostic_reports()
    {
        return $this->hasMany(DiagnosticReport::class, 'patient_code', 'patient_code');
    }

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }

    public function getAddressAttribute()
    {
        return $this->house_number.', '.$this->barangay.', '.$this->municipality.', '.$this->province.', '.$this->postal_code;
    }

    public function scopeSearch($query, $keyword = null)
    {
        if(empty($keyword)) return;

        return $query->where('patient_code', $keyword)
            ->orWhere('first_name', "LIKE", "%{$keyword}%")
            ->orWhere('last_name', "LIKE", "%{$keyword}%")
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE '%{$keyword}%'");
    }
}
