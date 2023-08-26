<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PatientStoreRequest extends FormRequest
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
            'patient_code' => 'required|string|min:2',
            'first_name' => 'required|string|min:2',
            'middle_name' => 'nullable',
            'last_name' => 'required|string|min:2',
            'password' => 'nullable',
            'philhealth_id' => 'nullable',
            'email' => 'required',
            'contact_number' => 'nullable',
            'gender' => 'required',
            'birth_date' => 'required',
        ];
    }

    public function getValidatorInstance()
    {
        $this->hashPassword();

        return parent::getValidatorInstance();
    }

    protected function hashPassword()
    {
        $this->merge([
            'password' => Hash::make($this->request->get('password'))
        ]);
    }
}
