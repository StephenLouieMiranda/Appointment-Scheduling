<?php

namespace App\Contracts;

interface UserServiceInterface
{
    /**
     * Get all users rows
     */
    public function getAllUsers();

    /**
     * Get specific User via user_code
     *
     * @param string $user_code
     * @return object
     */
    public function getUserViaUserCode($user_code);

    /**
     * Get Users with doctor relations
     *
     * @return object
     */
    public function getUsersWithDoctor();

    /**
     * Get users count that are Doctors
     *
     * @return int
     */
    public function getDoctorCount();

    /**
     * Get online users
     *
     * @return mixed
     */
    public function getOnlineDoctors();

    /**
     * Create a new User
     *
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data);

    /**
     * Update a User record
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateUserViaId($id, $data);

    /**
     * Update a specific column in User record
     *
     * @param string $identifier
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function updateUserColumnViaKey($identifier, $key, $value);

    /**
     * Delete a User record
     *
     * @param int $id
     * @return mixed
     */
    public function deleteUserViaId($id);


}