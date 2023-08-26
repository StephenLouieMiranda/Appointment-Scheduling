<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\AppointmentRepositoryInterface;
use App\Repositories\EloquentRepository;

class AppointmentRepository extends EloquentRepository implements AppointmentRepositoryInterface
{
    public function __construct(Appointment $model) {
        $this->model = $model;
        parent::__construct($model);
    }

    public function getAppointmentViaCode(string $appointment_code)
    {
        return $this->model->where('appointment_code', $appointment_code)->with(['user', 'patient'])->first();
    }

    public function getPatientAppointments(string $patient_code)
    {
        return $this->model->where('patient_code', $patient_code)->with(['user'])->get();
    }

    public function getDoctorAppointments(string $user_code)
    {
        return $this->model->where('user_code', $user_code)->with(['patient'])->get();
    }

    public function publishLink(string $user_code, string $link)
    {
        return $this->model->where('user_code', $user_code)->update(['link' => $link]);
    }

    public function approveAppointment($appointment_code, $data)
    {
        return $this->model->where('appointment_code', $appointment_code)->update([
            'link' => $data['link'],
            'status' => $data['status'],
        ]);
    }

    public function cancelAppointment($appointment_code, array $data)
    {
        return $this->model->where('appointment_code', $appointment_code)->update($data);
    }
}