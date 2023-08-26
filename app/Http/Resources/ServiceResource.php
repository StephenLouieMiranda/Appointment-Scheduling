<?php

namespace App\Http\Resources;

use App\Util\DateMappable;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'service_code' => $this->service_code,
            'name' => $this->name,
            'amount' => $this->amount,
        ], $this->default());
    }
}
