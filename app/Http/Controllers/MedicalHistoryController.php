<?php

namespace App\Http\Controllers;

use App\Contracts\MedicalHistoryServiceInterface;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public $medicalHistoryService;

    public function __construct(MedicalHistoryServiceInterface $medicalHistoryService) {
        $this->$medicalHistoryService = $medicalHistoryService;
    }

    public function index(Request $request)
    {
        # code...
    }

    public function store(Request $request)
    {
        # code...
    }

    public function update(Request $request)
    {
        # code...
    }

    public function update_via_key(Request $request)
    {
        # code...
    }
}
