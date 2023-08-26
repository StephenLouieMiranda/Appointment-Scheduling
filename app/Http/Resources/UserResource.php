<?php

namespace App\Http\Resources;

use App\Util\DateMappable;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'user_code' => $this->user_code,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'fullname' => "{$this->first_name} {$this->last_name}",
            'gender' => $this->gender,
            'contact_number' => $this->contact_number,
            'doctor' => $this?->doctor,
            'specialization' => $this->doctor?->specialization,
            'license_number' => $this->doctor?->license_number,
            'roles' => $this->roles->pluck('name'),
            'domain' => 'user',
            'status' => $this->status,
            'last_seen' => $this->last_seen,
        ]);
    }
}
