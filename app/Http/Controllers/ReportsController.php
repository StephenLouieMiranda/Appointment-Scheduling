<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Transaction;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function appointmentsDashboard(Request $request)
    {
        $defaultStartDate = now()->firstOfMonth()->format('Y-m-d 00:00:00');
        $defaultEndDate = now()->lastOfMonth()->format('Y-m-d 23:59:59');

        $appointments = Appointment::select('type', 'date', 'time', 'status', 'created_at')
            ->dates([request('start_date', $defaultStartDate), request('end_date', $defaultEndDate)])
            ->orderBy('date', 'asc') // via appointment date set
            ->get();

        $dashboardService = new DashboardService(
            request('span', 'day'),
            $appointments, 'chart_date',
            request('start_date', $defaultStartDate),
            request('end_date', $defaultEndDate),
        );

        $dashboardService
            ->setLocalLabel('On-site')
            ->setBorderColor('rgb(75, 192, 192)')
            ->setFill(false)
            ->setTension(0.5)
            ->addDataSet($appointments->where('type', 'outpatient_services'));

        $dashboardService
            ->setLocalLabel('Online')
            ->setBorderColor('rgb(0,125,6)')
            ->setFill(false)
            ->setTension(0.5)
            ->addDataSet($appointments->where('type', 'online_consultation'));

        return $dashboardService->generateChart();
    }

    public function transactionDashboard(Request $request)
    {
        $defaultStartDate = now()->firstOfMonth()->format('Y-m-d 00:00:00');
        $defaultEndDate = now()->lastOfMonth()->format('Y-m-d 23:59:59');

        $appointments = Transaction::select('id', 'status', 'created_at')
            ->dates([request('start_date', $defaultStartDate), request('end_date', $defaultEndDate)])
            ->orderBy('created_at', 'asc') // via appointment date set
            ->get();

        $dashboardService = new DashboardService(
            request('span', 'day'),
            $appointments, 'chart_date',
            request('start_date', $defaultStartDate),
            request('end_date', $defaultEndDate),
        );

        $dashboardService
            ->setLocalLabel('Unpaid')
            ->setBorderColor('rgb(75, 192, 192)')
            ->setFill(false)
            ->setTension(0.5)
            ->addDataSet($appointments->where('status', 'Unpaid'));

        $dashboardService
            ->setLocalLabel('Paid')
            ->setBorderColor('rgb(0,125,6)')
            ->setFill(false)
            ->setTension(0.5)
            ->addDataSet($appointments->where('status', 'paid'));

        $dashboardService
            ->setLocalLabel('Cancelled')
            ->setBorderColor('rgb(255,51,51)')
            ->setFill(false)
            ->setTension(0.5)
            ->addDataSet($appointments->where('status', 'cancelled'));

        return $dashboardService->generateChart();
    }
}
