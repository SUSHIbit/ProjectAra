<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status ?? 'pending';
        
        $query = Member::with('user');
        
        if ($status === 'pending') {
            $query->where('membership_requested', true)
                  ->where('is_approved', false);
        } elseif ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'all') {
            // No additional filter
        }
        
        $members = $query->latest()->paginate(15);
        
        return view('manager.members.index', compact('members', 'status'));
    }
    
    public function show(Member $member)
    {
        $member->load('user');
        
        return view('manager.members.show', compact('member'));
    }
    
    public function approve(Request $request, Member $member)
    {
        // Generate a unique membership number
        $membershipNumber = 'MEM-' . strtoupper(Str::random(8));
        
        $member->update([
            'is_approved' => true,
            'membership_start_date' => now(),
            'membership_end_date' => now()->addYear(),
            'membership_number' => $membershipNumber,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('manager.members.show', $member)
                         ->with('success', 'Membership approved successfully.');
    }
    
    public function reject(Request $request, Member $member)
    {
        $member->update([
            'membership_requested' => false,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('manager.members')
                         ->with('success', 'Membership request rejected.');
    }
}