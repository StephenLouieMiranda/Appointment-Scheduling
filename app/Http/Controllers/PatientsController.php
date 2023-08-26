<?php

namespace App\Http\Controllers;

use App\Contracts\PatientServiceInterface;
use App\Http\Requests\PatientStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\PatientRecordsResource;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientsController extends Controller
{
    public $patientService;

    public function __construct(PatientServiceInterface $patientService) {
        $this->patientService = $patientService;
    }

    public function search_patient(Request $request)
    {
        $patient_results = Patient::search($request->keyword)->get();

        if($patient_results)
        {
            return PatientResource::collection($patient_results)->response();
        }
        else {
            return response()->json([
                'api_code' => 'NOT_FOUND',
                'api_msg' => 'No patient found. You can be more specific with your search value.',
                'api_status' => false
            ], 404);
        }
    }

    public function check_email_availability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $validator->errors()->first(),
                'api_status' => false
            ], 400);
        }

        try {
            // Check if email exists, if not, return 200
            $is_available = !Patient::where('email', $request->email)->exists();

            if($is_available)
            {
                return response()->json([
                    'api_code' => 'SUCCESS',
                    'api_message' => 'Email address is available.',
                    'api_status' => true,
                ], 200);
            }

            throw new \Exception('Error in finding the email address.');

        } catch (\Throwable $th) {
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $th->getMessage(),
                'api_status' => false,
            ], 500);
        }
    }

    public function get_profile_data(Request $request)
    {
        return (new PatientResource($this->patientService->get_patient_profile($request->patientCodeFilter)))->response();
    }

    public function fetch_patient_records(Request $request)
    {
        return (new PatientRecordsResource($this->patientService->getPatientRecords($request->patientCodeFilter)))->response();
    }

    public function list(Request $request)
    {
        return PatientResource::collection($this->patientService->getAllPatients())->response();
    }

    public function store(PatientStoreRequest $request)
    {
        $patient = $this->patientService->createPatient($request->validated());

        $patient->assignRole('patient');

        return response()->json([
            'api_code' => 'SUCCESS',
            'api_msg' => 'Patient has been created successfully',
            'api_status' => true,
            'data' => new PatientResource($patient)
        ], 200);
    }

    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->merge(['status' => 'active']);
            $patient = $this->patientService->createPatient($request->all());
            info("Patient", [$patient]);

            if($patient)
            {
                DB::commit();

                event(new Registered($patient));

                return response()->json([
                    'api_code' => 'SUCCESS',
                    'api_msg' => 'Patient has been created successfully',
                    'api_status' => true,
                    'data' => new PatientResource($patient)
                ], 200);
            }

            throw new Exception('Patient registration failed.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $th->getMessage(),
                'api_status' => false,
            ], 500);
        }
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();

        $patient_updated = $this->patientService->updatePatientViaId($user->id, $request->validated());

        return (new PatientResource($this->patientService->get_patient_profile($request->patient_code)))->response();
    }
}
