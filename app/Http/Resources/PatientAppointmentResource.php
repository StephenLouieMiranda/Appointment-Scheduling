<?php

namespace App\Http\Resources;

use App\Util\DateMappable;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientAppointmentResource extends JsonResource
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
            'appointment_code' => $this->appointment_code,
            'patient_code' => $this->patient_code,
            'doctor' => $this->user,
            'type' => $this->type,
            'date' => $this->date,
            'time' => $this->time,
            'complaints' => $this->complaints,
            'link' => $this->link === null ? null : $this->link,
            'status' => $this->status,
            'schedule_status' => $this->schedule_status,
        ], $this->default());
    }
}
