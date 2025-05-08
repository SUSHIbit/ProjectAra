<?php

namespace App\Http\Controllers;

use App\Models\ClockRecord;
use App\Models\Member;
use App\Models\Payment;
use App\Models\ServiceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Main dashboard - redirects based on user role
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isManager()) {
            return redirect()->route('manager.dashboard');
        } elseif ($user->isEmployee()) {
            return redirect()->route('employee.dashboard');
        } else {
            // For public users
            return view('dashboard');
        }
    }

    /**
     * Employee dashboard
     */
    public function employeeDashboard(Request $request)
    {
        $user = $request->user();
        $isClockIn = $user->isClockIn();
        $clockRecord = $isClockIn ? $user->getLatestClockRecord() : null;
        
        $todayServiceRecords = ServiceRecord::where('employee_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->with(['user', 'service', 'payment'])
            ->latest()
            ->take(10)
            ->get();
            
        return view('employee.dashboard', compact('user', 'isClockIn', 'clockRecord', 'todayServiceRecords'));
    }
    
    /**
     * Manager dashboard
     */
    public function managerDashboard(Request $request)
    {
        // Count stats
        $pendingMembersCount = Member::where('membership_requested', true)
            ->where('is_approved', false)
            ->count();
            
        $activeEmployeesCount = DB::table('clock_records')
            ->whereDate('clock_in', now()->toDateString())
            ->whereNull('clock_out')
            ->distinct('user_id')
            ->count('user_id');
            
        $todaySales = Payment::whereDate('created_at', now()->toDateString())
            ->where('status', 'completed')
            ->sum('amount');
            
        $pendingServices = ServiceRecord::where('is_completed', false)
            ->count();
            
        // Recent payments
        $recentPayments = Payment::with(['serviceRecord.user', 'serviceRecord.employee', 'serviceRecord.service'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();
            
        return view('manager.dashboard', compact(
            'pendingMembersCount', 
            'activeEmployeesCount', 
            'todaySales', 
            'pendingServices', 
            'recentPayments'
        ));
    }
}