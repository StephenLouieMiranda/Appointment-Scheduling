<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'birth_date' => 'required',
            'birth_place' => 'required',
            'email' => 'required',
            'contact_number' => 'required',
            'house_number' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
        ];
    }
}
