<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Services') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('employee.services.process') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Available Services</h3>
                            
                            @if(count($services) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($services as $service)
                                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                                            <div class="flex items-start">
                                                <input type="checkbox" id="service_{{ $service->id }}" name="service_ids[]" value="{{ $service->id }}" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <div class="ml-3">
                                                    <label for="service_{{ $service->id }}" class="block text-sm font-medium text-gray-700">{{ $service->name }}</label>
                                                    <p class="text-sm text-gray-500">{{ $service->description }}</p>
                                                    <p class="text-sm font-medium text-gray-900 mt-1">RM {{ number_format($service->price, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
                                                No services available. Please contact a manager to add services.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-end mt-6">
                            @if(count($services) > 0)
                                <x-primary-button>
                                    {{ __('Proceed to Payment') }}
                                </x-primary-button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>