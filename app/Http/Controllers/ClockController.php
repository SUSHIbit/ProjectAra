<?php

namespace App\Http\Controllers;

use App\Models\ClockRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClockController extends Controller
{
    public function clockIn(Request $request)
    {
        $user = $request->user();
        
        // Check if already clocked in
        if ($user->isClockIn()) {
            return back()->with('error', 'You are already clocked in.');
        }
        
        // Create new clock record
        $clockRecord = ClockRecord::create([
            'user_id' => $user->id,
            'clock_in' => now(),
        ]);
        
        return back()->with('success', 'Clock in successful at ' . $clockRecord->clock_in->format('H:i'));
    }
    
    public function clockOut(Request $request)
    {
        $user = $request->user();
        
        // Check if clocked in
        if (!$user->isClockIn()) {
            return back()->with('error', 'You are not clocked in.');
        }
        
        // Get the latest active clock record
        $clockRecord = $user->getLatestClockRecord();
        $clockRecord->clock_out = now();
        $clockRecord->total_minutes = $clockRecord->calculateTotalMinutes();
        $clockRecord->save();
        
        return back()->with('success', 'Clock out successful. Total time: ' . 
            $this->formatMinutes($clockRecord->total_minutes));
    }
    
    public function attendanceReport(Request $request)
    {
        $date = $request->date ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');
        
        $attendanceRecords = ClockRecord::whereDate('clock_in', $date)
            ->with('user')
            ->orderBy('clock_in')
            ->get()
            ->map(function ($record) {
                $record->formatted_total_time = $record->total_minutes 
                    ? $this->formatMinutes($record->total_minutes) 
                    : 'Still Active';
                return $record;
            });
            
        $employees = User::where('role', 'employee')->orWhere('role', 'manager')->get();
        
        return view('manager.attendance', compact('attendanceRecords', 'employees', 'date'));
    }
    
    private function formatMinutes($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        return sprintf('%dh %dm', $hours, $remainingMinutes);
    }
}