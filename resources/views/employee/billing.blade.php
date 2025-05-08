<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Process Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900">Service Details</h3>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Customer</p>
                                <p class="text-md font-medium">{{ $serviceRecord->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Service</p>
                                <p class="text-md">{{ $serviceRecord->service->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Amount</p>
                                <p class="text-md font-medium">${{ number_format($serviceRecord->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date</p>
                                <p class="text-md">{{ $serviceRecord->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Select Payment Method</h3>
                        
                        <form method="POST" action="{{ route('employee.payment.process', $serviceRecord->id) }}">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50" onclick="document.getElementById('payment_method_qr').checked = true;">
                                    <div class="flex items-start">
                                        <input type="radio" id="payment_method_qr" name="payment_method" value="qr" class="mt-1" {{ old('payment_method') == 'qr' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <label for="payment_method_qr" class="block text-sm font-medium text-gray-700">QR Code Payment</label>
                                            <p class="text-sm text-gray-500">Customer will scan the employee's QR code</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50" onclick="document.getElementById('payment_method_card').checked = true;">
                                    <div class="flex items-start">
                                        <input type="radio" id="payment_method_card" name="payment_method" value="card" class="mt-1" {{ old('payment_method', 'card') == 'card' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <label for="payment_method_card" class="block text-sm font-medium text-gray-700">Card Payment</label>
                                            <p class="text-sm text-gray-500">Customer will pay with card (manually processed)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <x-primary-button>
                                    {{ __('Process Payment') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>