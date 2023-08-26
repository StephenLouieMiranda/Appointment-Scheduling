<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorScheduleResource extends JsonResource
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
            'id' => $this->id,
            'schedule_code' => $this->schedule_code,
            'appointment_code' => $this->appointment_code,
            'doctor_id' => $this->doctor_id,
            'day' => $this->day,
            'start_time_slot' => $this->start_time_slot,
            'end_time_slot' => $this->end_time_slot,
            'status' =>$this->status,
        ];
    }
}
