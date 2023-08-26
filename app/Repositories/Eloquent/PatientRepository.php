<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\EloquentRepository;
use App\Repositories\PatientRepositoryInterface;

class PatientRepository extends EloquentRepository implements PatientRepositoryInterface
{
    public function __construct(Patient $model) {
        $this->model = $model;
        parent::__construct($model);
    }

    public function get_patient_profile($patient_code)
    {
        return $this->model->where('patient_code', $patient_code)->first();
    }

    /**
     * Get Patient via patient_code
     *
     * @param string $patient_code
     * @return mixed
     */
    public function getPatientViaCode($patient_code)
    {
        return $this->model->where('patient_code', $patient_code)->first();
    }

    public function getPatientRecords($patient_code)
    {
        return $this->model->where('patient_code', $patient_code)
                        ->with(['medical_history', 'diagnostic_reports', 'clinical_notes', 'laboratory_tests'])
                        ->get();
    }
}