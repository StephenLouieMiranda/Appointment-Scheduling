<?php

namespace App\Repositories\Eloquent;

use App\Models\MedicalRecord;
use App\Repositories\EloquentRepository;
use App\Repositories\MedicalRecordRepositoryInterface;

class MedicalRecordRepository extends EloquentRepository implements MedicalRecordRepositoryInterface
{
    public $medicalRecord;

    public function __construct(MedicalRecord $model) {
        $this->model = $model;
        parent::__construct($model);
    }

    public function getMedicalRecordViaCode($record_code)
    {

    }

    public function getMedicalRecordViaPatientCode($patient_code)
    {

    }

    public function getMedicalRecordViaUserCode($user_code)
    {

    }

    public function getMedicalRecordViaVisitDate($visit_date)
    {

    }
}