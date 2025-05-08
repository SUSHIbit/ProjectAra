<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Sales Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Sales for {{ $date->format('F d, Y') }}</h3>
                        
                        <form method="GET" action="{{ route('manager.sales-daily') }}" class="flex">
                            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <x-primary-button class="ml-4">
                                {{ __('Filter') }}
                            </x-primary-button>
                        </form>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Summary</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Total Sales:</dt>
                                    <dd class="text-sm font-bold text-gray-900">${{ number_format($totalSales, 2) }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Transaction Count:</dt>
                                    <dd class="text-sm text-gray-900">{{ $transactionCount }}</dd>
                                </div>
                                @if($transactionCount > 0)
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Average Sale:</dt>
                                        <dd class="text-sm text-gray-900">${{ number_format($totalSales / $transactionCount, 2) }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Sales by Payment Method</h4>
                            @if(count($salesByPaymentMethod) > 0)
                                <div class="space-y-2">
                                    @foreach($salesByPaymentMethod as $paymentMethodSales)
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">{{ ucfirst($paymentMethodSales->payment_method) }}:</dt>
                                            <dd class="text-sm text-gray-900">
                                                ${{ number_format($paymentMethodSales->total, 2) }}
                                                <span class="text-xs text-gray-500">({{ $paymentMethodSales->count }} transactions)</span>
                                            </dd>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No sales data available.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Sales by Service</h4>
                        @if(count($salesByService) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($salesByService as $serviceSales)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $serviceSales->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $serviceSales->count }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">${{ number_format($serviceSales->total, 2) }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700">No sales data available.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-3">Transaction Details</h4>
                        @if(count($payments) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $payment->created_at->format('H:i:s') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $payment->serviceRecord->user->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $payment->serviceRecord->service->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $payment->serviceRecord->employee->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">${{ number_format($payment->amount, 2) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ ucfirst($payment->payment_method) }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700">No transactions available for the selected date.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>