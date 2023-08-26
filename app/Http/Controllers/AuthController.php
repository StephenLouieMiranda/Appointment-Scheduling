<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        auth()->shouldUse('web');

        info("AUTHENTICATING {$request->email}");
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            $user_code = Auth::user()->user_code;
            $user = User::where('user_code', $user_code)->with('roles')->first();

            info("AUTHENTICATED {$request->email}", [$user->roles]);

            return response()->json([
                'api_code' => 'AUTHENTICATED',
                'api_msg' => 'You have logged in successfully.',
                'api_status' => true,
                'data' => [
                    'user' => (new UserResource($user)),
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

    public function user(Request $request)
    {
        $user = User::where('user_code', $request->user()->user_code)->with(['roles', 'doctor'])->first();
        return (new UserResource($user))->response();
    }

    public function logout(Request $request)
    {
        auth('web')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();
        return response()->json(['message' => 'success'], 200);
    }

    public function verify(Request $request)
    {
        return response()->json(['message' => 'authenticated'], 200);
    }
}
