<?php

namespace App\Http\Resources;

use App\Util\DateMappable;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'user_code' => $this->user_code,
            'date' => $this->date,
            'time' => $this->time,
            'schedule' => $this->schedule, // Attribute
            'type' => $this->type,
            'complaints' => $this->complaints,
            'link' => $this->link === 'null' ? null : $this->link,
            'is_available_now' => $this->is_available_now,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'schedule_status' => $this->schedule_status,
            'doctor' => $this->user,
            'patient' => $this->patient,
        ], $this->default());
    }

    private function getStatus($status)
    {
        list($hours, $minutes, $seconds) = explode(':', $this->time);

        switch (true) {
            case
                Carbon::parse($this->date)->addHours($hours)->addMinutes($minutes)->diffInMinutes(now()) <= 5
                && $this->status == 'approved':
                return 'Upcoming';
                break;
            case
                Carbon::parse($this->date)->addHours($hours)->addMinutes($minutes)->diffInMinutes(now()) <= 5
                && $this->status == 'approved':
                return 'approved';
                break;

            default:
                # code...
                break;
        }
    }
}
