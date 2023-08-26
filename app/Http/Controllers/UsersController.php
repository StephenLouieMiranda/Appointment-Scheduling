<?php

namespace App\Http\Controllers;

use App\Contracts\UserServiceInterface;
use App\Http\Requests\UpdateDoctorProfileRequest;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\RolesResource;
use App\Http\Resources\UserDoctorResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersCountViaRolesResource;
use App\Models\Doctor;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    private $userService;

    public function __construct(UserServiceInterface $userService) {
        $this->userService = $userService;
    }

    public function doctor_count(Request $request)
    {
        return response()->json([
            'api_msg' => "Fetch doctor count success",
            'api_code' => "FETCH_SUCCESS",
            'api_status' => true,
            'data' => [
                'doctorCount' => $this->userService->getDoctorCount(),
            ]
        ]);
    }

    public function all(Request $request)
    {
        // All users
        return UserResource::collection($this->userService->getAllUsers())->response();
    }

    /**
     * Get the Doctors for Appointment (Patient domain)
     */
    public function doctor_list(Request $request)
    {
        try {
            $doctor_indepth = function($q) { $q->with('schedules'); };
            $doctors = User::whereHas('doctor')->with(['doctor' => $doctor_indepth])->get();

            if($doctors)
            {
                return response()->json([
                    'api_code' => 'SUCCESS',
                    'api_msg' => 'Fetching of doctors success.',
                    'api_status' => true,
                    'data' => [
                        'doctors' => UserDoctorResource::collection($doctors),
                    ]
                ], 200);
            }

            throw new \Exception('There was an error getting doctors data. Please try again later.');
        } catch (\Throwable $th) {
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $th->getMessage(),
                'api_status' => false,
            ], 500);
        }
    }

    public function get_doctor(Request $request)
    {
        $doctor_indepth = function($q) { $q->with('schedules'); };
        $doctor = User::where('user_code', $request->userCodeFilter)->with(['doctor' => $doctor_indepth])->first();

        if($doctor)
        {
            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Fetched doctor successfully.',
                'api_status' => true,
                'data' => [
                    'doctor' => new UserResource($doctor)
                ]
            ], 200);
        }
    }

    public function get_user_profile(Request $request)
    {
        return (new UserResource($this->userService->getUserViaUserCode($request->userCodeFilter)))->response();
    }

    public function get_online_doctors(Request $request)
    {
        return UserResource::collection($this->userService->getOnlineDoctors())->response();
    }

    public function get_roles(Request $request)
    {
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'patient')->get();
        return RolesResource::collection($roles)->response();
    }

    public function get_users_count_via_role(Request $request)
    {
        return UsersCountViaRolesResource::collection($this->userService->getAllUsers());
    }

    public function store(UserStoreRequest $request)
    {
        return (new UserResource($this->userService->createUser($request->validated())))->response();
    }

    // Update own profile
    public function update_profile(UserProfileUpdateRequest $request)
    {
        $user_updated = $this->userService->updateUserViaId($request->id, $request->validated());

        if($user_updated)
        {
            return response()->json([
                'api_code' => 'UPDATED',
                'api_msg' => 'Profile has been updated!',
                'api_status' => true,
                'data' => [
                    'user' => (new UserResource($this->userService->getUserViaUserCode($request->user_code)))
                ]
            ]);
        }
    }

    // If user is doctor and has Doctor role
    public function update_doctor_profile(UpdateDoctorProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $doctor_updated = Doctor::where('user_code', $request->user_code)->update($request->validated());

            if($doctor_updated)
            {
                $doctor = Doctor::where('user_code', $request->user_code)->first();
                DB::commit();
                return response()->json([
                    'api_code' => 'UPDATED',
                    'api_msg' => 'Doctor Information has been updated!',
                    'api_status' => true,
                    'data' => [
                        'doctor' => (new DoctorResource($doctor))
                    ]
                ]);
            }

            throw new \Exception('Doctor information has failed to be updated.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $th->getMessage(),
                'api_status' => false,
            ], 500);
        }
    }

    public function update_via_key(Request $request)
    {
        $this->userService->updateUserColumnViaKey($request->identifier, $request->key, $request->value);
        return (new UserResource($this->userService->getUserViaUserCode($request->identifier['user_code'])))->response();
    }

}
