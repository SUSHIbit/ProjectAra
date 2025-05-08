<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function employees()
    {
        $employees = User::where('role', 'employee')
                        ->orWhere('role', 'manager')
                        ->latest()
                        ->paginate(15);
                        
        return view('manager.employees.index', compact('employees'));
    }
    
    public function qrForm(User $user)
    {
        if (!$user->isEmployee()) {
            abort(404);
        }
        
        return view('manager.employees.qr-form', compact('user'));
    }
    
    public function uploadQr(Request $request, User $user)
    {
        if (!$user->isEmployee()) {
            abort(404);
        }
        
        $request->validate([
            'qr_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Delete old QR code if exists
        if ($user->qr_code_path) {
            Storage::disk('public')->delete($user->qr_code_path);
        }
        
        // Store new QR code
        $path = $request->file('qr_image')->store('qr_codes', 'public');
        
        $user->update([
            'qr_code_path' => $path,
        ]);
        
        return redirect()->route('manager.employees')
                         ->with('success', 'QR code uploaded successfully.');
    }
}