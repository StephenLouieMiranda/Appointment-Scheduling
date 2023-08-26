<?php

namespace App\Models\Scopes;

trait MedicalRecordScopes
{
    public function scopeSearch($query, $search_value = null)
    {
        if(empty($search_value)) return;

        return $query->where('record_code', $search_value)
            ->orWhere(function($sub_query) use($search_value) {
                $sub_query->patientName($search_value)
                    ->orWhere(fn($q) => $q->doctorName($search_value) );
            })
            ->orWhere(function($sub_query) use($search_value) {
                $sub_query->diagnosis($search_value)
                        ->symptoms($search_value);
            });
    }

    public function scopeRecordCode($query, $record_code = null)
    {
        if(empty($record_code)) return;

        if(is_array($record_code))
        {
            return $query->whereIn('record_code', $record_code);
        } else {
            return $query->where('record_code', $record_code);
        }
    }

    public function scopePatientCode($query, $patient_code = null)
    {
        if(empty($patient_code)) return;

        return $query->where('patient_code', $patient_code);
    }

    public function scopePatientName($query, $patient_name = null)
    {
        if(empty($patient_name)) return;

        return $query->whereHas('patient', function($q) use($patient_name) {
            $q->where('first_name', "LIKE", "%{$patient_name}%")
                ->orWhere('last_name', "LIKE", "%{$patient_name}%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE '%{$patient_name}%'");
        });
    }

    public function scopeDoctorId($query, $doctor_id = null)
    {
        if(empty($doctor_id)) return;

        return $query->where('doctor_id', $doctor_id);
    }

    public function scopeDoctorName($query, $doctor_name = null)
    {
        if(empty($doctor_name)) return;

        return $query->whereHas('doctor', function($q) use($doctor_name){
            $q->whereHas('user', function($sub_query) use($doctor_name){
                $sub_query->where('first_name', "LIKE", "%{$doctor_name}%")
                    ->orWhere('last_name', "LIKE", "%{$doctor_name}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE '%{$doctor_name}%'");
            });
        });
    }

    public function scopeVisitDate($query, $visit_date = null)
    {
        if(empty($visit_date)) return;

        $date = now()->parse($visit_date)->format('Y-m-d');

        return $query->where('visit_date', $date);
    }

    public function scopeSymptoms($query, $symptoms = null)
    {
        if(empty($symptoms)) return;

        return $query->where('symptoms', "LIKE", "%{$symptoms}%");
    }

    public function scopeDiagnosis($query, $diagnosis = null)
    {
        if(empty($diagnosis)) return;

        return $query->where('diagnosis', "LIKE", "%{$diagnosis}%");
    }

    public function scopeTreatmentNotes($query, $treatment_notes = null)
    {
        if(empty($treatment_notes)) return;

        return $query->where('treatment_notes', "LIKE", "%{$treatment_notes}%");
    }
}