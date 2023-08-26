<?php

namespace App\Http\Controllers;

use App\Contracts\AppointmentServiceInterface;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Notifications\AppointmentApprovedNotification;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    public $appointmentService;

    public function __construct(AppointmentServiceInterface $appointmentService) {
        $this->appointmentService = $appointmentService;
    }

    public function all(Request $request)
    {
        return AppointmentResource::collection($this->appointmentService->getAllAppointments())->response();
    }

    public function get_appointment(Request $request)
    {
        $appointment = Appointment::where('appointment_code', $request->appointmentCodeFilter)->first();
        if($appointment)
        {
            return response()->json([
                'api_code' => 'success',
                'api_msg' => 'Fetch Appointment Success',
                'data' => [
                    'data' => new AppointmentResource($appointment),
                ]
            ], 200);
        }
        else {

        }
    }

    public function get_appointment_via_room_id(Request $request)
    {
        $appointment = Appointment::where('link', $request->room_id)->first();

        if($appointment)
        {
            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Appointment fetched successfully',
                'api_status' => true,
                'data' => [
                    'data' => new AppointmentResource($appointment),
                ]
            ]);
        }
    }

    public function list(Request $request)
    {
        return AppointmentResource::collection($this->appointmentService->getDoctorApppointmentsViaUserCode($request->userCodeFilter ?? " "))->response();
    }

    public function doctor_upcoming_appointments(Request $request)
    {
        return AppointmentResource::collection($this->appointmentService->getDoctorUpcomingAppointments($request->all()))->response();
    }

    public function verify(Request $request)
    {
        $auth_user = auth()->user();

        $appointment = Appointment::where('link', $request->link)->where('user_code', $auth_user->user_code)->first();

        if($appointment)
        {
            return response()->json([
                'api_code' => 'VERIFIED',
                'api_msg' => 'Appointment user verified successfully.',
                'api_status' => true,
            ], 200);
        }
    }

    public function verify_room_id(Request $request)
    {
        $appointment = Appointment::where('link', $request->room_id)->first();

        if($appointment)
        {
            if($appointment->schedule_status == 'Over')
            {

            }
            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Room ID Verified',
                'api_status' => true
            ], 200);
        }
        else {
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => 'Room ID is incorrect or not found.',
                'api_status' => false
            ], 404);
        }
    }

    public function store(Request $request)
    {
        // $this->appointmentService->createAppointment($request->except('yes'));
    }

    public function declare_done(Request $request)
    {
        $appointment = Appointment::where('appointment_code', $request->appointment_code)->with('schedule')->first();

        if($appointment)
        {
            $appointment->status = 'done';
            $appointment->save();

            $schedule = Schedule::where('schedule_code', $appointment->schedule_code)->first();
            $schedule->status = 'active';

            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Appointment declared as done',
                'api_status' => true,
            ], 200);
        }
    }

    public function publish_link(Request $request)
    {
        return (new AppointmentResource($this->appointmentService->publishLink($request->user_code, $request->link)))->response();
    }

    public function approve(Request $request)
    {
        $appointment_updated = $this->appointmentService->approveAppointment($request->all());

        if($appointment_updated)
        {
            $appointment = Appointment::where('appointment_code', $request->appointment_code)->with(['patient', 'user'])->first();

            $appointment->patient->notify(new AppointmentApprovedNotification($appointment));

            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Appointment approved successfully.',
                'api_status' => true,
                'data' => [
                    'appointment' => new AppointmentResource($appointment),
                ]
            ], 200);
        }
    }

    public function update(Request $request)
    {

    }

    public function cancel(Request $request)
    {
        try {
            $appointment = Appointment::where('appointment_code', $request->appointment_code)->with(['schedule'])->first();

            if($appointment)
            {

            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
