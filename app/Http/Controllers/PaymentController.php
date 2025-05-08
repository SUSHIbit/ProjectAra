<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ServiceRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function billing(ServiceRecord $serviceRecord)
    {
        // Check if payment already exists
        if ($serviceRecord->payment) {
            return redirect()->route('employee.receipt', $serviceRecord->payment->id);
        }
        
        return view('employee.billing', compact('serviceRecord'));
    }
    
    public function processPayment(Request $request, ServiceRecord $serviceRecord)
    {
        $request->validate([
            'payment_method' => 'required|in:qr,card',
        ]);
        
        // Check if payment already exists
        if ($serviceRecord->payment) {
            return redirect()->route('employee.receipt', $serviceRecord->payment->id);
        }
        
        $payment = Payment::create([
            'service_record_id' => $serviceRecord->id,
            'amount' => $serviceRecord->amount,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'transaction_id' => 'TXN-' . Str::random(10),
        ]);
        
        // Mark service record as completed
        $serviceRecord->update(['is_completed' => true]);
        
        // Redirect to receipt page after payment is processed
        return redirect()->route('employee.receipt', $payment->id)
            ->with('success', 'Payment processed successfully.');
    }
    
    public function generateReceipt(Payment $payment)
    {
        $serviceRecord = $payment->serviceRecord;
        
        return view('employee.receipt', compact('payment', 'serviceRecord'));
    }
    
    public function downloadReceiptPdf(Payment $payment)
    {
        $serviceRecord = $payment->serviceRecord;
        
        $pdf = PDF::loadView('pdfs.receipt', compact('payment', 'serviceRecord'));
        
        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }
}