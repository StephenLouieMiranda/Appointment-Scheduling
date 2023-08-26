<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\EloquentRepository;
use App\Repositories\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    public function __construct(User $model) {
        $this->model = $model;
        parent::__construct($model);
    }

    public function getUserViaUserCode($user_code)
    {
        return $this->model->where('user_code', $user_code)->first();
    }

    public function getUsersWithDoctorRelations()
    {
        return $this->model->with(['doctor'])->whereHas('doctor')->get();
    }

    public function getDoctorCount()
    {
        return $this->model->whereHas('doctor')->count();
    }

    public function getOnlineDoctors()
    {
        return $this->model->whereHas('doctor')->where('id', '!=', auth()->user()->id)->whereNotNull('last_seen')->orderBy('last_seen', 'DESC')->get();
    }
}