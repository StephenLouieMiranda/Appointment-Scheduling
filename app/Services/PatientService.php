<?php

namespace App\Services;

use App\Contracts\PatientServiceInterface;
use App\Models\Patient;
use App\Repositories\PatientRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class PatientService implements PatientServiceInterface
{
    public $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository) {
        $this->patientRepository = $patientRepository;
    }

    public function get_patient_profile($patient_code)
    {
        return $this->patientRepository->get_patient_profile($patient_code);
    }

    public function getAllPatients()
    {
        return $this->patientRepository->all();
    }

    public function getPatientViaCode($patient_code)
    {
        return $this->patientRepository->getPatientViaCode($patient_code);
    }

    public function getPatientRecords($patient_code)
    {
        return $this->patientRepository->getPatientRecords($patient_code);
    }

    public function createPatient($data)
    {
        try
        {
            $patient = new Patient();
            $patient->patient_code = $data['patient_code'];
            $patient->email = $data['email'];
            $patient->email_verified_at = null;
            $patient->password = Hash::make($data['password']);
            $patient->first_name = $data['first_name'];
            $patient->middle_name = $data['middle_name'] ?? null;
            $patient->last_name = $data['last_name'];
            $patient->birth_date = Carbon::parse($data['birth_date'])->format('Y-m-d');
            $patient->birth_place = $data['birth_place'];
            $patient->gender = $data['gender'];
            $patient->contact_number = $data['contact_number'];
            $patient->house_number = $data['house_number'];
            $patient->barangay = $data['barangay'];
            $patient->municipality = $data['municipality'];
            $patient->province = $data['province'];
            $patient->postal_code = $data['postal_code'];

            $patient->save();

            if($patient)
            {
                return $patient;
            }
        } catch(\Throwable $th) {

        }
    }

    public function updatePatientViaId($id, $data)
    {
        return $this->patientRepository->updateViaId($id, $data);
    }

    public function deletePatientViaId($id)
    {
        $this->patientRepository->deleteViaId($id);
    }
}