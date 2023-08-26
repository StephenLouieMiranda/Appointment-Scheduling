<?php

namespace App\Http\Resources;

use App\Util\DateMappable;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    use DateMappable;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge([
            'patient_code' => $this->patient_code,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'fullname' => $this->fullname,
            'age' => $this->age,
            'gender' => $this->gender,
            'contact_number' => $this->contact_number,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'house_number' => $this->house_number,
            'barangay' => $this->barangay,
            'municipality' => $this->municipality,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'status' => $this->status,
            'address' => $this?->address,
            'medical_records' => $this?->medical_records,
            'domain' => 'patient',
        ]);
    }
}
