<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRecord;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    public function searchCustomerForm()
    {
        return view('employee.search-customer');
    }
    
    public function searchCustomer(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3',
        ]);
        
        $search = $request->input('search');
        
        $customers = User::where('role', 'public')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->with('member')
            ->take(20)
            ->get();
            
        return view('employee.search-customer', compact('customers', 'search'));
    }
    
    public function customerServiceForm(Request $request)
    {
        $customerId = $request->customer_id;
        $customer = null;
        
        if ($customerId) {
            $customer = User::findOrFail($customerId);
        }
        
        $services = Service::where('is_active', true)->get();
        
        return view('employee.customer-service', compact('customer', 'services'));
    }
    
    public function storeService(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $service = Service::findOrFail($request->service_id);
        
        $serviceRecord = ServiceRecord::create([
            'user_id' => $request->customer_id,
            'employee_id' => $request->user()->id,
            'service_id' => $request->service_id,
            'amount' => $service->price,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('employee.billing', $serviceRecord->id)
            ->with('success', 'Service record created successfully.');
    }
}