<?php

namespace App\Http\Controllers;

use App\Contracts\AppointmentServiceInterface;
use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\PatientAppointmentResource;
use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientsAppointmentController extends Controller
{
    private $appointmentService;

    public function __construct(AppointmentServiceInterface $appointmentService) {
        $this->appointmentService = $appointmentService;
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
        return AppointmentResource::collection($this->appointmentService->getPatientAppointmentsViaPatientCode($request->fPatientCode))->response();
    }

    public function verify(Request $request)
    {
        $auth_user = auth()->user();

        $appointment = Appointment::where('link', $request->link)->where('patient_code', $auth_user->patient_code)->first();

        if($appointment)
        {
            return response()->json([
                'api_code' => 'VERIFIED',
                'api_msg' => 'Appointment patient verified successfully.',
                'api_status' => true,
            ], 200);
        }
    }

    public function verify_room_id(Request $request)
    {
        $appointment = Appointment::where('link', $request->room_id)->first();

        if($appointment)
        {
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

    public function store(AppointmentStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $appointment = $this->appointmentService->createAppointment($request->all());

            if($appointment)
            {
                // Update schedule status
                $schedule = Schedule::where('schedule_code', $request->schedule_code)->first();
                $schedule->appointment_code = $request->appointment_code;
                $schedule->status = 'booked';
                $schedule->save();

                DB::commit();

                return response()->json([
                    'api_code' => 'SUCCESS',
                    'api_msg' => 'Appointment created successfully.',
                    'api_status' => true,
                    'data' => [
                        'appointment' => new PatientAppointmentResource($appointment),
                    ]
                ], 200);
            }

            throw new \Exception('Appointment creation failed.');

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'api_code' => 'ERROR',
                'api_msg' => 'Appointment creation failed.',
                'api_status' => false,
                'data' => [
                    'error' => $e->getMessage(),
                ]
            ]);
        }
    }

    public function update(Request $request)
    {

    }

    public function cancel(Request $request)
    {
        $appointment = Appointment::where('appointment_code', $request->appointment_code)->first();
        $schedule = Schedule::where('appointment_code', $request->appointment_code)->first();

        if($appointment)
        {
            $appointment->status = 'cancelled';
            $appointment->remarks = $request->remarks;
            $appointment->save();

            $schedule->status = 'active';
            $schedule->appointment_code = null;
            $schedule->save();

            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Appointment cancelled successfully.',
                'api_status' => true,
                'data' => [
                    'appointment' => new PatientAppointmentResource($appointment),
                ]
            ], 200);
        }
    }
}
