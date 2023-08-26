<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SendGrid\Mail\Mail;

class AuthPatientController extends Controller
{
    public function authenticate(Request $request)
    {
        auth()->shouldUse('patient');

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('patient')->attempt($credentials)) {
            $request->session()->regenerate();

            $patient_code = Auth::user()->patient_code;
            $patient = Patient::where('patient_code', $patient_code)->with('roles')->first();

            if($patient->email_verified_at == null) {
                Auth::logout();
                return response()->json([
                    'api_code' => 'UNVERIFIED',
                    'api_msg' => 'Your email is not yet verified.',
                    'api_status' => false,
                    'hint' => 'Check your email and verify your account.',
                ], 401);
            }

            return response()->json([
                'api_code' => 'AUTHENTICATED',
                'api_msg' => 'You have logged in successfully.',
                'api_status' => true,
                'data' => [
                    'patient' => (new PatientResource($patient)),
                ]
            ], 200);
        }

        return response()->json([
            'api_code' => 'UNAUTHORIZED',
            'api_msg' => 'Your email or password is incorrect.',
            'api_status' => false,
            'hint' => 'Check your email and password and try again',
        ], 401);
    }

    public function logout(Request $request)
    {
        auth('patient')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();
        return response()->json(['message' => 'success'], 200);
    }

    public function verify(Request $request)
    {
        return response()->json(['message' => 'authenticated'], 200);
    }
}
