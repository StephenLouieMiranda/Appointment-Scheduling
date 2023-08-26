<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthPatientController;
use App\Http\Controllers\MedicalRecordsController;
use App\Http\Controllers\PatientsAppointmentController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Requests\PatientStoreRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('unauthenticated')->post('/', function (Request $request) {
    return response()->json(['error' => 'unauthenticated'], 401);
});


Route::as('v1.')
    ->prefix('v1')
    ->group(function() {

    // Patient Registration ednpoint
    Route::post('/patient/register', [PatientsController::class, 'register'])->name('register');

    // Admin Modules Routes
    Route::prefix('user')
        ->as('user.')
        ->group(function() {

        // Authentication
        Route::controller(AuthController::class)
        ->as('auth.')
        ->prefix('/auth')
        ->group(function () {
            Route::post('authenticate', 'authenticate')->name('authenticate');
            Route::post('logout', 'logout')->name('logout');
            Route::middleware('auth:sanctum')->get('verify', 'verify')->name('verify');

            Route::middleware('auth:sanctum')->get('/authenticated-user', 'user');
        });

        // Authenticated routes
        Route::middleware('auth:sanctum')
            ->group(function() {

            Route::controller(UsersController::class)
            ->prefix('doctors')
            ->as('doctors.')
            ->group(function(){
                Route::get('/get_online_doctors', 'get_online_doctors')->name('get_online_doctors');
            });

            // Patients Module
            Route::controller(PatientsController::class)
            ->prefix('patients')
            ->as('patients.')
            ->group(function() {
                Route::get('/search', 'search_patient')->name('search_patient');
                Route::get('/list', 'list')->name('list')->middleware('permission:patients.read');
                Route::get('/fetch-patient-records', 'fetch_patient_records')->name('fetch_patient_records');

                Route::post('/store', 'store')->name('store')->middleware('permission:patients.store');
            });

            // Appointments
            Route::controller(AppointmentsController::class)
                ->prefix('appointments')
                ->as('appointments.')
                ->group(function() {
                    Route::get('/all', 'all')->name('all')->middleware('permission:appointments.all');
                    Route::get('/get-appointment', 'get_appointment')->name('get_appointment');
                    Route::get('/get-appointment-via-room-id', 'get_appointment_via_room_id')->name('get_appointment_via_room_id');
                    Route::get('/list', 'list')->name('list')->middleware('permission:appointments.list');
                    Route::get('/verify', 'verify')->name('verify');
                    Route::get('/upcoming-today', 'doctor_upcoming_appointments')->name('upcoming_today')->middleware('permission:appointments.list');

                    Route::post('/declare-done', 'declare_done')->name('declare_done');
                    Route::post('/verify-room-id', 'verify_room_id')->name('verify_room_id');
                    Route::post('/store', 'store')->name('store')->middleware('permission:appointments.store');
                    Route::post('/publish-link', 'publish_link')->name('publish_link')->middleware('permission:appointments.publish_link');
                    Route::post('/approve', 'approve')->name('approve');
                    Route::post('/update', 'update')->name('update')->middleware('permission:appointments.update');
                    Route::post('/cancel', 'cancel')->name('cancel')->middleware('permission:appointments.cancel');
            });

            // User Management
            Route::controller(UsersController::class)
                ->prefix('user-management')
                ->as('user_management.')
                ->group(function() {
                    Route::get('/accounts-all', 'all')->name('all');
                    Route::get('/get-doctor-count', 'doctor_count')->name('doctor_count');
                    Route::get('/get-user-profile', 'get_user_profile')->name('get_user_profile');

                    Route::get('/get-roles', 'get_roles')->name('get_roles');

                    Route::post('/store', 'store')->name('store');

                    // Update own profile
                    Route::post('/update-profile', 'update_profile')->name('update_profile');
                    // Update doctor profile if user is a doctor
                    Route::post('/update-doctor-profile', 'update_doctor_profile')->name('update_doctor_profile');

                    Route::post('/update-via-key', 'update_via_key')->name('update_via_key');
                });

            Route::controller(SchedulesController::class)
                ->prefix('schedule')
                ->as('schedule.')
                ->group(function() {
                    Route::get('/get-schedule', 'get_schedule')->name('get_schedule');
                    Route::get('/get-time-slots', 'get_time_slots')->name('get_time_slots');

                    Route::post('/store', 'store')->name('store');
                    Route::post('/delete', 'delete')->name('delete');
                });

            // Medical Records Module
            Route::controller(MedicalRecordsController::class)
                ->prefix('medical-records')
                ->as('medical_records.')
                ->group(function() {
                    Route::get('/index', 'index')->name('index');
                    Route::get('/search-record', 'query')->name('query');
                    Route::get('/show', 'show')->name('show');

                    Route::post('/store', 'store')->name('store');
                    Route::post('/download/{code}', 'downloadRecord')->name('download_record');
                });

            // Services Module
            Route::controller(ServicesController::class)
                ->prefix('services')
                ->as('services.')
                ->group(function() {
                    Route::get('/all', 'all')->name('all');
                    Route::get('/list', 'list')->name('list');
                    Route::get('/get-service', 'get_service')->name('get_service');

                    Route::post('/store', 'store')->name('store');
                    Route::post('/update', 'update')->name('update');
                    Route::post('/delete', 'delete')->name('delete');
                });

            Route::controller(TransactionsController::class)
                ->prefix('transactions')
                ->as('transactions.')
                ->group(function() {
                    Route::get('/', 'all')->name('all');

                    Route::post('/store', 'store')->name('store');
                });


            Route::controller(ReportsController::class)
                ->prefix('reports')
                ->as('reports.')
                ->group(function() {
                    Route::get('/appointments-visualize', 'appointmentsDashboard')->name('appointment_dashboard');
                    Route::get('/transaction-visualize', 'transactionDashboard')->name('transaction_dashboard');
                });
        });
    });

    Route::prefix('patient')
        ->as('patient.')
        ->group(function () {

        // Authentication
        Route::controller(AuthPatientController::class)
        ->as('auth.')
        ->prefix('/auth')
        ->group(function () {
            Route::post('authenticate', 'authenticate')->name('authenticate');
            Route::post('logout', 'logout')->name('logout');
            Route::middleware(['auth:sanctum'])->get('verify', 'verify')->name('verify');

            Route::middleware(['auth:sanctum'])->get('/authenticated-patient', function (Request $request) {
                return $request->user();
            });

            // Route::get('/email/verify/{id}/{hash}', 'verify')->middleware(['auth', 'signed'])->name('verification.verify');

            Route::get("/verify-email/{id}/{hash}", VerifyEmailController::class)->name("verification.verify")->middleware(['signed']);
            Route::post("/resend", [VerifyEmailController::class, 'resend']);
        });

        // Email availability and verification
        Route::controller(PatientsController::class)
            ->prefix('email')
            ->as('email.')
            ->group(function() {
                Route::post('/check-email-availability', 'check_email_availability')->name('check_email_availability');
            });

        // Authenticated routes
        Route::middleware(['auth:sanctum'])
            ->group(function() {

            // Profile
            Route::controller(PatientsController::class)
                ->prefix('profile')
                ->as('profile.')
                ->middleware('auth:sanctum')
                ->group(function() {
                    Route::get('get_profile_data', 'get_profile_data')->name('get_profile_data');
                    Route::post('update', 'update')->name('update');
            });

            // Appointments
            Route::controller(PatientsAppointmentController::class)
                ->prefix('appointments')
                ->as('appointments.')
                ->middleware('auth:sanctum')
                ->group(function() {
                    Route::get('/get-appointment-via-room-id', 'get_appointment_via_room_id')->name('get_appointment_via_room_id');
                    Route::get('/list', 'list')->name('list');
                    Route::get('/verify', 'verify')->name('verify');
                    Route::post('/verify-room-id', 'verify_room_id')->name('verify_room_id');
                    Route::post('/store', 'store')->name('store');
                    Route::post('/update', 'update')->name('update');
                    Route::post('/cancel', 'cancel')->name('cancel');
            });

            // Users
            Route::controller(UsersController::class)
                ->prefix('users')
                ->as('users.')
                ->group(function() {
                    Route::get('/doctor-list', 'doctor_list')->name('doctor_list');
                    Route::get('/get-doctor-data', 'get_doctor')->name('get_doctor');
            });

            Route::controller(MedicalRecordsController::class)
                ->prefix('medical-records')
                ->as('medical_records.')
                ->group(function() {
                    Route::get('/get-medical-records', 'get_medical_records')->name('get_medical_records');
                });
        });
    });
});