<?php

namespace App\Services;

use App\Contracts\AppointmentServiceInterface;
use App\Models\Appointment;
use App\Notifications\AppointmentNotification;
use App\Repositories\AppointmentRepositoryInterface;
use Illuminate\Http\Client\Request;

class AppointmentService implements AppointmentServiceInterface
{
    public $appointmentRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository) {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAllAppointments()
    {
        return $this->appointmentRepository->all();
    }

    public function getAppointmentViaCode($appointment_code)
    {
        return $this->appointmentRepository->getAppointmentViaCode($appointment_code);
    }

    public function getDoctorUpcomingAppointments(array $filters = [])
    {
        return $this->appointmentRepository->addFilter('today', $filters['todayFilter'])->paginate(50);
    }

    public function getDoctorApppointmentsViaUserCode(string $user_code)
    {
        return $this->appointmentRepository->getDoctorAppointments($user_code);
    }

    public function getPatientAppointmentsViaPatientCode(string $patient_code)
    {
        return $this->appointmentRepository->getPatientAppointments($patient_code);
    }

    public function publishLink(string $user_code, string $link)
    {
        return $this->appointmentRepository->publishLink($user_code, $link);
    }

    public function createAppointment(array $data)
    {
        $new_appointment = Appointment::create($data);

        if($new_appointment)
        {
            $appointment = $this->getAppointmentViaCode($data['appointment_code']);
            // Notify the User ('Doctor')
            $appointment->user->notify((new AppointmentNotification($appointment))->onQueue('database'));

            return $new_appointment;
        }
    }

    public function approveAppointment($data)
    {
        $user = auth()->user();

        return $this->appointmentRepository->approveAppointment($data['appointment_code'], $data);
    }

    public function updateAppointmentViaId($id, array $data)
    {
        return $this->appointmentRepository->updateViaId($id, $data);
    }

    public function cancelAppointment($appointment_code, array $data)
    {
        return $this->appointmentRepository->cancelAppointment($appointment_code, $data);
    }
}