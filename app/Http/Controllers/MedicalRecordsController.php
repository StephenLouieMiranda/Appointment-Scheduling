<?php

namespace App\Http\Controllers;

use App\Contracts\MedicalRecordsServiceInterface;
use App\Http\Resources\MedicalRecordsResource;
use App\Http\Resources\MedicalRecordWithPatientResource;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Services\LetterGeneratorService;
use App\Util\MedicalRecordPdfView;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicalRecordsController extends Controller
{
    public $medicalRecordService;

    public function __construct(MedicalRecordsServiceInterface $medicalRecordService) {
        $this->medicalRecordService = $medicalRecordService;
    }

    public function index(Request $request)
    {
        $indepth_doctor = function($q) { $q->with('user'); };
        $medical_records = MedicalRecord::latest()->with(['patient', 'doctor' => $indepth_doctor])->limit(50)->get();

        if($medical_records)
        {
            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Fetched latest 50 records successfully.',
                'api_status' => true,
                'data' => [
                    'medical_records' => MedicalRecordsResource::collection($medical_records)
                ]
            ], 200);
        }

        return response()->json([
            'api_code' => 'FAILED',
            'api_msg' => 'Fetching of records failed.',
            'api_status' => false,
        ], 500);
    }

    public function query(Request $request)
    {
        $results = MedicalRecordsResource::collection($this->medicalRecordService->searchRecord($request->all()));

        if($results)
        {
            if($results->count() > 0)
            {
                return response()->json([
                    'api_code' => 'SUCCESS',
                    'api_msg' => 'Fetched records successfully.',
                    'api_status' => true,
                    'data' => [
                        'medical_records' => $results
                    ]
                ], 200);
            } else {
                return response()->json([
                    'api_code' => 'FAILED',
                    'api_msg' => 'Fetching of records failed.',
                    'api_status' => false,
                ], 204);
            }
        }
    }

    // FOR PATIENTS
    public function get_medical_records(Request $request)
    {
        $indepth_doctor = function($q) { $q->with('user'); };
        $records = MedicalRecord::where('patient_code', $request->patientCodeFilter)->with(['patient', 'doctor' => $indepth_doctor])->get();

        if($records)
        {
            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Fetched record successfully.',
                'api_status' => true,
                'data' => [
                    'medical_records' => MedicalRecordsResource::collection($records)
                ]
            ], 200);
        }
    }

    public function show(Request $request)
    {
        $indepth_doctor = function($q) { $q->with('user'); };
        $record = MedicalRecord::where('record_code', $request->recordCodeFilter)->with(['patient', 'doctor' => $indepth_doctor ])->first();

        if($record)
        {
            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Fetched record successfully.',
                'api_status' => true,
                'data' => [
                    'medical_record' => new MedicalRecordsResource($record)
                ]
            ], 200);
        }

        return response()->json([
            'api_code' => 'FAILED',
            'api_msg' => 'Fetching of record failed.',
            'api_status' => false,
        ], 500);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $doctor = Doctor::where('user_code', $user->user_code)->first();
        info("Doctor", [$doctor]);

        $record_code = "MR" . Str::upper(Str::random(8));

        $request->merge(['record_code' => $record_code]);
        $request->merge(['doctor_id' => $doctor->id]);

        try {
            $medical_record = new MedicalRecordWithPatientResource($this->medicalRecordService->storeMedicalRecord($request->all()));

            if($medical_record)
            {
                return response()->json([
                    'api_code' => 'SUCCESS',
                    'api_msg' => 'Medical record created successfully.',
                    'api_status' => true,
                    'data' => [
                        'medical_record' => $medical_record
                    ]
                ], 200);
            }

            throw new \Exception("Medical record creation failed.");

        } catch (\Throwable $th) {
            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => $th->getMessage(),
                'api_status' => false,
            ], 500);
        }

    }

    public function update(Request $request)
    {

    }

    public function downloadRecord(Request $request, $code)
    {
        $record = MedicalRecord::where('record_code', $code)->with(['patient', 'doctor'])->firstOrFail();

        $record->date_issued = now()->format('Y-m-d H:i:s');
        $record->save();

        $pdfView = new MedicalRecordPdfView($record, $record->patient, $record->doctor);
        $pdfView->setView('pdf.medical-history-pdf');

        $letterGenerator = new LetterGeneratorService($pdfView);
        $letterGenerator->setName("{$record->record_code}_{$record->created_at}");

        return $letterGenerator->download();
    }
}
