<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Receipt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Payment Receipt</h3>
                            <p class="text-sm text-gray-500">Transaction ID: {{ $payment->transaction_id }}</p>
                        </div>
                        <div>
                            <a href="{{ route('employee.receipt.pdf', $payment->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                    
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="text-lg font-bold">{{ config('app.name') }}</h4>
                                    <p class="text-sm text-gray-600">{{ now()->format('F d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Receipt #: {{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-sm text-gray-600">Date: {{ $payment->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 border-b">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700">Customer</h5>
                                    <p class="mt-1">{{ $serviceRecord->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $serviceRecord->user->email }}</p>
                                    @if($serviceRecord->user->phone)
                                        <p class="text-sm text-gray-600">{{ $serviceRecord->user->phone }}</p>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700">Payment Details</h5>
                                    <p class="mt-1">Method: {{ ucfirst($payment->payment_method) }}</p>
                                    <p class="text-sm text-gray-600">Status: {{ ucfirst($payment->status) }}</p>
                                    <p class="text-sm text-gray-600">Transaction ID: {{ $payment->transaction_id }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-3">Service Details</h5>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $serviceRecord->service->name }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ $serviceRecord->service->description ?? 'No description' }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 text-right">
                                            ${{ number_format($serviceRecord->amount, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            Total:
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                            ${{ number_format($payment->amount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 text-center">
                            <p class="text-sm text-gray-600">Thank you for your business!</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('employee.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>