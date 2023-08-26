<?php

namespace App\Contracts;

interface AppointmentServiceInterface
{

    /**
     * Get all appointments rows
     * @return LengthAwarePaginator
     */
    public function getAllAppointments();

    /**
     * Get Appointment row via appointment code,
     * contains the user and patient relationship
     *
     * @param string $appointment_code
     */
    public function getAppointmentViaCode($appointment_code);

    /**
     * Get Doctor upcoming appointments today
     *
     * @param array $filters
     */
    public function getDoctorUpcomingAppointments(array $filters);

    /**
     * Get Appointments of Doctor via user_code
     * @param string $user_code
     */
    public function getDoctorApppointmentsViaUserCode(string $user_code);

    /**
     * Get Appointments of Patient via patient_code
     *
     * @param string $patient_code
     */
    public function getPatientAppointmentsViaPatientCode(string $patient_code);

    /**
     * Save the consultation link to database
     *
     * @param string $user_code
     * @param string $link
     */
    public function publishLink(string $user_code, string $link);

    /**
     * Create new appointment record
     * @param array $data
     * @return object
     */
    public function createAppointment(array $data);

    /**
     * Approve the appointment
     *
     * @param array $data
     * @return mixed
     */
    public function approveAppointment($data);

    /**
     * Update an appointment row via ID
     * @param int $id
     * @param array $data
     * @return object
     */
    public function updateAppointmentViaId($id, array $data);

    /**
     * Cancel an appointment record
     * @param int $appointment_code
     * @param array @data
     */
    public function cancelAppointment($appointment_code, array $data);
}