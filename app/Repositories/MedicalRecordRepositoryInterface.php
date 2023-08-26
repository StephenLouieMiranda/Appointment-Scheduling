<?php

namespace App\Repositories;

interface MedicalRecordRepositoryInterface
{
    /**
     * Get Medical Record via record code
     *
     * @param string $record_code Medical Record code
     * @return mixed
     */
    public function getMedicalRecordViaCode($record_code);

    /**
     * Get Medical Record via patient code
     *
     * @param string $patient_code
     * @return mixed
     */
    public function getMedicalRecordViaPatientCode($patient_code);

    /**
     * Get Medical Record via user code
     *
     * @param string $user_code User Code of the Doctor
     * @return mixed
     */
    public function getMedicalRecordViaUserCode($user_code);

    /**
     * Get Medical Record via visit date
     *
     * @param date $visit_date Visit date in format of Y-m-d
     * @return mixed
     */
    public function getMedicalRecordViaVisitDate($visit_date);
}