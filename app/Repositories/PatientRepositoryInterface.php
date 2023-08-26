<?php

namespace App\Repositories;

interface PatientRepositoryInterface
{
    /**
     * Get Patient profile data via patient code
     *
     * @param string $patient_code Patient's patient code
     * @return mixed
     */
    public function get_patient_profile($patient_code);

    /**
     * Get Patient via patient_code
     *
     * @param string $patient_code
     * @return mixed
     */
    public function getPatientViaCode($patient_code);

    /**
     * Get Patient records which includes
     * medical history, diagnostic reports,
     * clinical notes, and laboratory tests
     *
     * @param string $patient_code
     * @return mixed
     */
    public function getPatientRecords($patient_code);
}