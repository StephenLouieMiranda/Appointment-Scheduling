<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomPatientMailVerificationRequest;
use App\Models\Patient;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerifyEmailController extends Controller
{
    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/patient/login';

    public function __construct() {
        // $this->middleware('auth:sanctum');
        // $this->middleware('signed')->only('verify');
        // $this->middleware('throttle:600,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(CustomPatientMailVerificationRequest $request)
    {
        $patient = Patient::where('id', $request['id'])->first();

        $code = Str::uuid();
        if ($patient->hasVerifiedEmail()) {
            return redirect()->to(config('app.app_frontend_url') . "/patient/verify-failed/${code}/already-verified");
        }

        if ($patient->markEmailAsVerified()) {
            event(new Verified($patient));
        }

        return redirect()->to(config('app.app_frontend_url') . "/patient/verify-success");
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
      $userID = $request['id'];
      $user = Patient::findOrFail($userID);
      $user->email_verified_at = date("Y-m-d H:i:s");
      $user->save();

      return response()->json([
        'api_code' => 'verified',
        'api_msg' => 'Email has been verified.',
        'api_status' => true
      ], 200);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {

        $patient = Patient::where('email', $request->email)->first();

        if ($patient->hasVerifiedEmail()) {
            return response()->json([
                'api_code' => 'INVALID',
                'api_msg' => 'Email is already verified.',
                'api_status' => false
            ], 422);
        }

        $patient->sendEmailVerificationNotification();

        return response()->json([
            'api_code' => 'RESENT',
            'api_msg' => 'We have e-mailed your verification link!',
            'api_status' => true,
        ], 200);
    }
}
