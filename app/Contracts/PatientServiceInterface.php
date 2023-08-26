<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PatientServiceInterface
{
    /**
     * Get Patient's profile data.
     *
     * @param string $patient_code Patient's patient code
     * @return mixed
     */
    public function get_patient_profile($patient_code);

    /**
     * Get all patients rows
     */
    public function getAllPatients();

    /**
     * Get specific patient via code
     *
     * @param string $patient_code
     * @return mixed
     */
    public function getPatientViaCode($patient_code);

    /**
     * Get patient records that includes
     * medical history, diagnostic reports, etc.
     *
     * @param string $patient_code
     * @return mixed
     */
    public function getPatientRecords($patient_code);

    /**
     * Create a new Patient
     *
     * @param array $data
     * @return mixed
     */
    public function createPatient(array $data);

    /**
     * Update a Patient record
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updatePatientViaId($id, $data);

    /**
     * Delete a Patient record
     *
     * @param int $id
     * @return mixed
     */
    public function deletePatientViaId($id);
}