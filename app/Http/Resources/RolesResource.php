<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->getDescription($this->name)
        ];
    }

    private function getDescription($role)
    {
        switch ($role) {
            case 'admin':
                return "Mostly have access to modules not locked for Super Admin";
                break;
            case 'doctor':
                return "Has access to Patients, Appointments, and Results module";
                break;
            case 'nurse':
                return "Has access to Patients, Appointments, User Management, and Results module";
                break;
            default:
                break;
        }
    }
}
