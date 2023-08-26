<?php

use App\Http\Controllers\MedicalRecordsController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Authenticated routes
Route::middleware('auth')
    ->controller(MedicalRecordsController::class)
    ->group(function() {
        Route::get('medical-records/download/{code}', 'downloadRecord');
    });


Route::get("patients/verify-email/{id}/{hash}", VerifyEmailController::class)->name("verification.verify");