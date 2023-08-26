<?php

namespace App\Services;

use App\Contracts\UserServiceInterface;
use App\Models\Doctor;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService implements UserServiceInterface
{
    public $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function getUserViaUserCode($user_code)
    {
        return $this->userRepository->getUserViaUserCode($user_code);
    }

    public function getUsersWithDoctor()
    {
        return $this->userRepository->getUsersWithDoctorRelations();
    }

    public function getDoctorCount()
    {
        return $this->userRepository->getDoctorCount();
    }

    public function getOnlineDoctors()
    {
        return $this->userRepository->getOnlineDoctors();
    }

    public function createUser(array $data)
    {
        DB::beginTransaction();
        $user_code = Str::upper(Str::random(12));

        try {
            $user = $this->userRepository->create([
                'user_code' => $user_code,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['sex'],
                'contact_number' => $data['contact_number'],
                'status' => $data['status']
            ]);

            if(!empty($data['role']))
            {
                $user->assignRole($data['role']);
                if($data['role'] == 'doctor')
                {
                    $doctor = Doctor::create([
                        'user_code' => $user_code,
                        'specialization' => $data['specialization'],
                        'sub_specialization' => $data['sub_specialization'],
                        'license_number' => $data['license_number'],
                        'status' => $data['status'],
                    ]);
                }
            }
            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateUserViaId($id, $data)
    {
        return $this->userRepository->updateViaId($id, $data);
    }

    public function updateUserColumnViaKey($identifier, $key, $value)
    {
        return $this->userRepository->updateKeyFromUniqueField($identifier, $key,  $value);
    }

    public function deleteUserViaId($id)
    {
        return $this->userRepository->deleteViaId($id);
    }
}
