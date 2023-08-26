<?php

namespace App\Repositories;

interface AppointmentRepositoryInterface
{
    /**
     * Get Appointment via appointment code,
     * contains the user and patient relationship
     *
     * @param string $appointment_code
     * @return object
     */
    public function getAppointmentViaCode(string $appointment_code);

    /**
     * Update an appointment record status to cancelled
     *
     * @param string $appointment_code
     * @param array $data
     * @return object
     */
    public function cancelAppointment($appointment_code, array $data);

    /**
     * Get all appointments of doctor
     *
     * @param string $user_code
     * @return object
     */
    public function getDoctorAppointments(string $user_code);

    /**
     * Get all appointments of patient
     *
     * @param string $patient_code
     * @return object
     */
    public function getPatientAppointments(string $patient_code);

    /**
     * Save the link of the appointment
     *
     * @param string $user_code
     * @param string $link
     */
    public function publishLink(string $user_code, string $link);

    /**
     * Approve the appointment
     *
     * @param string $appointment_code The appointment code
     * @param array $data Should contain the 'link' and the 'status'
     *
     * @return mixed
     */
    public function approveAppointment($appointment_code, $data);
}