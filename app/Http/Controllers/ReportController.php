<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailySales(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : now();
        
        // Get daily sales - FIX AMBIGUOUS COLUMN REFERENCES
        $salesByService = Payment::whereDate('payments.created_at', $date->toDateString())
            ->where('payments.status', 'completed')
            ->join('service_records', 'payments.service_record_id', '=', 'service_records.id')
            ->join('services', 'service_records.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as count'), DB::raw('sum(payments.amount) as total'))
            ->groupBy('services.name')
            ->get();
            
        $salesByPaymentMethod = Payment::whereDate('payments.created_at', $date->toDateString())
            ->where('payments.status', 'completed')
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_method')
            ->get();
            
        $totalSales = Payment::whereDate('payments.created_at', $date->toDateString())
            ->where('payments.status', 'completed')
            ->sum('amount');
            
        $transactionCount = Payment::whereDate('payments.created_at', $date->toDateString())
            ->where('payments.status', 'completed')
            ->count();
            
        // Get all payments for the day
        $payments = Payment::whereDate('payments.created_at', $date->toDateString())
            ->where('payments.status', 'completed')
            ->with(['serviceRecord.user', 'serviceRecord.employee', 'serviceRecord.service'])
            ->latest('payments.created_at')
            ->get();
            
        return view('manager.reports.daily-sales', compact(
            'date',
            'salesByService',
            'salesByPaymentMethod',
            'totalSales',
            'transactionCount',
            'payments'
        ));
    }
    
    public function monthlySales(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        // Sales by day - FIX AMBIGUOUS COLUMN REFERENCES
        $dailySales = Payment::whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.status', 'completed')
            ->select(
                DB::raw('DATE(payments.created_at) as date'),
                DB::raw('count(*) as count'),
                DB::raw('sum(payments.amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Sales by service
        $salesByService = Payment::whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.status', 'completed')
            ->join('service_records', 'payments.service_record_id', '=', 'service_records.id')
            ->join('services', 'service_records.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as count'), DB::raw('sum(payments.amount) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->get();
            
        // Sales by payment method
        $salesByPaymentMethod = Payment::whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.status', 'completed')
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_method')
            ->get();
            
        $totalSales = Payment::whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.status', 'completed')
            ->sum('amount');
            
        $transactionCount = Payment::whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.status', 'completed')
            ->count();
            
        return view('manager.reports.monthly-sales', compact(
            'year',
            'month',
            'startDate',
            'endDate',
            'dailySales',
            'salesByService',
            'salesByPaymentMethod',
            'totalSales',
            'transactionCount'
        ));
    }
}