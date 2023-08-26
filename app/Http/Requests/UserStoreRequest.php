<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'sex' => 'required',
            'contact_number' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            'specialization' => 'required_if:role,doctor',
            'sub_specialization' => 'nullable',
            'license_number' => 'required_if:role,doctor',
            'status' => 'required',
        ];
    }
}
