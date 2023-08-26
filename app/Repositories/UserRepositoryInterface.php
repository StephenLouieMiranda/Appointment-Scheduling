<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    /**
     * Get a User via user_code
     *
     * @param string $user_code
     * @return object
     */
    public function getUserViaUserCode($user_code);

    /**
     * Get a User via user_code with Doctor relationship
     *
     * @return object
     */
    public function getUsersWithDoctorRelations();

    /**
     * Get the count of users that are doctor
     *
     * @return int
     */
    public function getDoctorCount();

    /**
     * Get the online users
     *
     * @return mixed
     */
    public function getOnlineDoctors();
}