<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRecord;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeServiceController extends Controller
{
    public function selectService()
    {
        $services = Service::where('is_active', true)->get();
        
        return view('employee.services.select', compact('services'));
    }
    
    public function processSelectedServices(Request $request)
    {
        $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
        ]);
        
        $selectedServices = Service::whereIn('id', $request->service_ids)->get();
        $totalAmount = $selectedServices->sum('price');
        
        // Store in session for payment processing
        session(['selected_services' => $selectedServices, 'total_amount' => $totalAmount]);
        
        return redirect()->route('employee.payment');
    }
    
    public function showPaymentPage()
    {
        $selectedServices = session('selected_services');
        $totalAmount = session('total_amount');
        
        if (!$selectedServices || $totalAmount <= 0) {
            return redirect()->route('employee.services')->with('error', 'Please select services first.');
        }
        
        $employee = auth()->user();
        
        return view('employee.services.payment', compact('selectedServices', 'totalAmount', 'employee'));
    }
    
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:qr,card',
        ]);
        
        $selectedServices = session('selected_services');
        $totalAmount = session('total_amount');
        
        if (!$selectedServices || $totalAmount <= 0) {
            return redirect()->route('employee.services')->with('error', 'Please select services first.');
        }
        
        // Create a service record for each selected service
        $employee = $request->user();
        $serviceRecords = [];
        
        // In a real app, you'd have a customer selection step
        // For now, we'll use the customer from session or a default
        $customerId = session('customer_id', 4); // Default to customer ID 4 if not set
        
        foreach ($selectedServices as $service) {
            $serviceRecord = ServiceRecord::create([
                'user_id' => $customerId,
                'employee_id' => $employee->id,
                'service_id' => $service->id,
                'amount' => $service->price,
                'is_completed' => true,
            ]);
            
            $serviceRecords[] = $serviceRecord;
        }
        
        // Create a payment record linked to the first service record
        // In a real system, you might have a more complex relationship for multiple services
        $payment = Payment::create([
            'service_record_id' => $serviceRecords[0]->id,
            'amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'transaction_id' => 'TXN-' . Str::random(10),
        ]);
        
        // Clear the session data for services and total
        session()->forget(['selected_services', 'total_amount']);
        
        // Redirect to the receipt page with success message
        return redirect()->route('employee.receipt', $payment->id)
            ->with('success', 'Payment processed successfully.');
    }
}