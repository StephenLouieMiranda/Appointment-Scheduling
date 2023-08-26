<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'schedule_code' => 'required',
            'appointment_code' => 'required',
            'patient_code' => 'required',
            'user_code' => 'required',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i:s',
            'type' => 'required',
            'link' => 'nullable',
            'complaints' => 'nullable',
            'status' => 'required',
        ];
    }
}
