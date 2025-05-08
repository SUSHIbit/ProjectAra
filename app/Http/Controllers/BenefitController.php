<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\User;
use Illuminate\Http\Request;

class BenefitController extends Controller
{
    public function index()
    {
        $benefits = Benefit::latest()->paginate(10);
        
        return view('manager.benefits.index', compact('benefits'));
    }
    
    public function create()
    {
        return view('manager.benefits.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        Benefit::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('manager.benefits')
                         ->with('success', 'Benefit created successfully.');
    }
    
    public function edit(Benefit $benefit)
    {
        return view('manager.benefits.edit', compact('benefit'));
    }
    
    public function update(Request $request, Benefit $benefit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        $benefit->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active ?? false,
        ]);
        
        return redirect()->route('manager.benefits')
                         ->with('success', 'Benefit updated successfully.');
    }
    
    public function destroy(Benefit $benefit)
    {
        $benefit->delete();
        
        return redirect()->route('manager.benefits')
                         ->with('success', 'Benefit deleted successfully.');
    }
    
    public function assignForm(Benefit $benefit)
    {
        $employees = User::where('role', 'employee')
                        ->orWhere('role', 'manager')
                        ->get();
                        
        $assignedUsers = $benefit->users()->pluck('users.id')->toArray();
        
        return view('manager.benefits.assign', compact('benefit', 'employees', 'assignedUsers'));
    }
    
    public function assign(Request $request, Benefit $benefit)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'expiry_date' => 'nullable|date',
        ]);
        
        // Detach all existing users
        $benefit->users()->detach();
        
        // Attach selected users
        foreach ($request->user_ids as $userId) {
            $benefit->users()->attach($userId, [
                'assigned_date' => now(),
                'expiry_date' => $request->expiry_date,
                'is_active' => true,
            ]);
        }
        
        return redirect()->route('manager.benefits')
                         ->with('success', 'Benefit assigned to employees successfully.');
    }
}