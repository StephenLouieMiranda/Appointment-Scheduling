<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorScheduleResource;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchedulesController extends Controller
{
    public function get_schedule(Request $request)
    {
        $indepth_schedule = function($q) { $q->active(); };

        $doctor = Doctor::where('user_code', $request->userCodeFilter)->with(['schedules' => $indepth_schedule])->first();

        return response()->json([
            'api_code' => 'SUCCESS',
            'api_msg' => 'Schedule fetched successfully.',
            'api_status' => true,
            'data' => [
                'schedules' => DoctorScheduleResource::collection($doctor->schedules),
            ]
        ]);
    }

    public function get_time_slots(Request $request)
    {
        $time_slots = Schedule::where('doctor_id', $request->doctor_id)->where('day', $request->day)->active()->get();

        if($time_slots)
        {
            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Time slots fetched successfully.',
                'api_status' => true,
                'data' => [
                    'time_slots' => DoctorScheduleResource::collection($time_slots),
                ]
            ]);
        }
    }

    public function store(Request $request)
    {
        $request_validated = $request->validate([
            'doctor_id' => 'required',
            'day' => 'required',
            'start_time_slot' => 'required',
            'end_time_slot' => 'required',
        ]);

        $doctor = Doctor::where('id', $request->doctor_id)->first();

        if(!$doctor)
        {
            return response()->json([
                'api_code' => 'INVALID',
                'api_msg' => 'Invalid user code, please try a different one.',
                'api_status' => false,
            ], 403);
        }

        DB::beginTransaction();
        try {
            $schedule = Schedule::upsert([
                [
                    'schedule_code' => "DS" . Str::upper(Str::random(6)),
                    'doctor_id' => $doctor->id,
                    'day' => Carbon::parse($request_validated['day'])->dayOfWeek,
                    'start_time_slot' => $request_validated['start_time_slot'],
                    'end_time_slot' => $request_validated['end_time_slot'],
                    'status' => 'active'
                ]
            ], ['schedule_code', 'doctor_id', 'day', 'start_time_slot', 'end_time_slot'], ['start_time_slot', 'end_time_slot']);

            if($schedule)
            {
                DB::commit();

                // Return all the schedules
                $schedules = Schedule::where('doctor_id', $doctor->id)->active()->get();

                return response()->json([
                    'api_code' => 'CREATED',
                    'api_msg' => 'Schedule has been created!',
                    'api_status' => true,
                    'data' => [
                        'schedules' => DoctorScheduleResource::collection($schedules),
                    ]
                ], 200);
            }

            throw new \Exception('Schedule has failed to be created.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $th->getMessage(),
                'api_status' => false,
            ], 400);
        }
    }

    public function update_schedule(Request $request)
    {
        $user = User::where('user_code', Auth::user()->user_code)->with(['doctor', 'schedule'])->first();

        DB::beginTransaction();
        try {
            $schedule_updated = Schedule::where('doctor_id', $request->doctor_id)->update();

            if($schedule_updated)
            {
                DB::commit();

                $schedule = Schedule::where('id', $user->doctor->id)->first();
                return response()->json([
                    'api_code' => 'UPDATED',
                    'api_msg' => 'Schedule has been updated!',
                    'api_status' => true,
                ], 200);
            }

            throw new \Exception('Schedule has failed to be updated.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $th->getMessage(),
                'api_status' => false,
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        $doctor = Doctor::where('user_code', $request->user_code)->first();

        DB::beginTransaction();
        try {
            $schedule = Schedule::where('id', $request->id)->first();
            $schedule->status = 'deleted';
            $schedule->save();

            if($schedule)
            {
                DB::commit();

                $schedules = Schedule::where('doctor_id', $doctor->id)->active()->get();

                return response()->json([
                    'api_code' => 'DELETED',
                    'api_msg' => 'Schedule has been deleted!',
                    'api_status' => true,
                    'data' => [
                        'schedules' => DoctorScheduleResource::collection($schedules),
                    ]
                ], 200);
            }
            throw new \Exception('Schedule has failed to be deleted.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $th->getMessage(),
                'api_status' => false,
            ], 400);
        }
    }
}
