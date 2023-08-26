<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientRecordsResource extends JsonResource
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
            'patient_code' => $this->patient_code,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'fullname' => $this->fullname,
            'age' => $this->age,
            'address' => $this->address,
            'medical_history' => $this?->medical_history,
            'diagnostic_reports' => $this?->diagnostic_reports,
            'clinical_notes' => $this?->clinical_notes,
            'laboratory_tests' => $this?->laboratory_tests,
            'status' => $this->status,
        ];
    }
}
