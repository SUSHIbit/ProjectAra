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
                        <h3 class="text-lg font-medium text-gray-900">Selected Services</h3>
                        <div class="mt-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($selectedServices as $service)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $service->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $service->description ?? 'No description' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                RM {{ number_format($service->price, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            Total:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                            RM {{ number_format($totalAmount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Select Payment Method</h3>
                        
                        <form method="POST" action="{{ route('employee.payment.process') }}">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div id="qr-option" class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50" onclick="document.getElementById('payment_method_qr').checked = true; toggleQrCode(true);">
                                    <div class="flex items-start">
                                        <input type="radio" id="payment_method_qr" name="payment_method" value="qr" class="mt-1" {{ old('payment_method') == 'qr' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <label for="payment_method_qr" class="block text-sm font-medium text-gray-700">QR Code Payment</label>
                                            <p class="text-sm text-gray-500">Customer will scan the employee's QR code</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50" onclick="document.getElementById('payment_method_card').checked = true; toggleQrCode(false);">
                                    <div class="flex items-start">
                                        <input type="radio" id="payment_method_card" name="payment_method" value="card" class="mt-1" {{ old('payment_method', 'card') == 'card' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <label for="payment_method_card" class="block text-sm font-medium text-gray-700">Card Payment</label>
                                            <p class="text-sm text-gray-500">Customer will pay with card (manually processed)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="qr-code-display" class="mt-6 hidden">
                                <h4 class="text-md font-medium text-gray-700 mb-2">QR Code</h4>
                                @if($employee->qr_code_path)
                                    <div class="max-w-xs mx-auto">
                                        <img src="{{ Storage::url($employee->qr_code_path) }}" alt="Payment QR Code" class="w-full border rounded-lg">
                                        <p class="mt-2 text-center text-sm text-gray-600">Scan this QR Code to pay</p>
                                        <p class="mt-1 text-center text-lg font-bold">RM {{ number_format($totalAmount, 2) }}</p>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700">
                                                    No QR code uploaded for this employee. Please contact your manager to upload a QR code.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <a href="{{ route('employee.services') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-3 py-2">
                                    {{ __('Cancel') }}
                                </a>
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

    <script>
        // JavaScript to toggle QR code display
        function toggleQrCode(show) {
            const qrCodeDisplay = document.getElementById('qr-code-display');
            if (show) {
                qrCodeDisplay.classList.remove('hidden');
            } else {
                qrCodeDisplay.classList.add('hidden');
            }
        }

        // Initialize based on default selection
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('payment_method_qr').checked) {
                toggleQrCode(true);
            }
        });
    </script>
</x-app-layout>