<?php

namespace App\Providers;

use App\Contracts\AppointmentServiceInterface;
use App\Contracts\MedicalHistoryServiceInterface;
use App\Contracts\MedicalRecordsServiceInterface;
use App\Contracts\PatientServiceInterface;
use App\Contracts\PdfViewInterface;
use App\Contracts\UserServiceInterface;
use App\Services\AppointmentService;
use App\Services\MedicalHistoryService;
use App\Services\MedicalRecordService;
use App\Services\PatientService;
use App\Services\UserService;
use App\Util\Pdf\EndorsementPdfView;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PatientServiceInterface::class, PatientService::class);
        $this->app->bind(AppointmentServiceInterface::class, AppointmentService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(MedicalHistoryServiceInterface::class, MedicalHistoryService::class);
        $this->app->bind(MedicalRecordsServiceInterface::class, MedicalRecordService::class);
        $this->app->bind(PdfViewInterface::class, EndorsementPdfView::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
