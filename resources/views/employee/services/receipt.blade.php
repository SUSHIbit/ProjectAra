<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Receipt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Payment Successful</h3>
                        <p class="text-sm text-gray-600">Transaction ID: {{ $payment->transaction_id }}</p>
                    </div>
                    
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">Payment of RM {{ number_format($payment->amount, 2) }} has been processed successfully.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Payment Details</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($payment->payment_method) }}</dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                <dd class="mt-1 text-sm text-gray-900">RM {{ number_format($payment->amount, 2) }}</dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('M d, Y H:i A') }}</dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($payment->status) }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div class="border rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h4 class="text-md font-medium text-gray-700">Service Details</h4>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $payment->serviceRecord->service->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $payment->serviceRecord->service->description ?? 'No description' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                RM {{ number_format($payment->serviceRecord->amount, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('employee.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>