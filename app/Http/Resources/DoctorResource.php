<?php

namespace App\Http\Resources;

use App\Util\DateMappable;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'license_number' => $this->license_number,
            'specialization' => $this->specialization,
            'sub_specialization' => $this->sub_specialization,
            'status' => $this->status,
        ], $this->default());
    }
}
