<?php

namespace App\Services;

use App\Contracts\MedicalRecordsServiceInterface;
use App\Repositories\MedicalRecordRepositoryInterface;

class MedicalRecordService implements MedicalRecordsServiceInterface
{
    public $medicalRecordRepository;

    public function __construct(MedicalRecordRepositoryInterface $medicalRecordRepository) {
        $this->medicalRecordRepository = $medicalRecordRepository;
    }

    public function searchRecord($filters = [])
    {
        $indepth_doctor = function($q) { $q->with('user'); };

        return $this->medicalRecordRepository->addFilter('search', $filters['query'])
                                            ->addFilter('visitDate', $filters['visit_date'] ?? null)
                                            ->with(['patient', 'doctor' => $indepth_doctor])
                                            ->paginate(50);
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

    public function storeMedicalRecord($data)
    {
        return $this->medicalRecordRepository->create($data);
    }

    public function updateMedicalRecord($identifier, $data)
    {

    }
}