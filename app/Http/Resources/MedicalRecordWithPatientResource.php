<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordWithPatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'record_code' => $this->record_code,
            'patient_code' => $this->patient_code,
            'doctor_id' => $this->doctor_id,
            'visit_date' => $this->visit_date,
            'symptoms' => $this->symptoms,
            'diagnosis' => $this->diagnosis,
            'medications' => $this->medications,
            'treatment_notes' => $this->treatment_notes,
            'test_result' => $this->test_result,
            'next_appointment' => $this->next_appointment,
            'patient' => $this?->patient,
        ];
    }
}
