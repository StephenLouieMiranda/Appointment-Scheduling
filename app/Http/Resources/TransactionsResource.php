<?php

namespace App\Http\Resources;

use App\Util\DateMappable;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
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
            'transaction_code' => $this->transaction_code,
            'patient_code' => $this->patient_code,
            'amount' => $this->amount,
            'service' => $this->service,
            'status' => $this->status,
            'patient' => $this->whenLoaded('patient', function () {
                return new PatientResource($this->patient);
            }),
        ], $this->default());
    }
}
