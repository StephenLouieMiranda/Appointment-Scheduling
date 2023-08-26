<?php

namespace App\Repositories\Eloquent;

use App\Models\MedicalHistory;
use App\Repositories\MedicalHistoryRepositoryInterface;

class MedicalHistoryRepository implements MedicalHistoryRepositoryInterface
{
    public $model;

    public function __construct(MedicalHistory $model) {
        $this->model = $model;
    }
}