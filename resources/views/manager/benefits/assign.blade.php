<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Benefit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $benefit->name }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $benefit->description }}</p>
                    </div>
                    
                    <form method="POST" action="{{ route('manager.benefits.assign', $benefit->id) }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="user_ids" :value="__('Select Employees')" />
                            <div class="mt-1 max-h-60 overflow-y-auto bg-gray-50 p-2 rounded-md">
                                @foreach($employees as $employee)
                                    <div class="py-2 border-b">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="user_ids[]" value="{{ $employee->id }}"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                   {{ in_array($employee->id, $assignedUsers) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-900">{{ $employee->name }}</span>
                                            <span class="ml-2 text-xs text-gray-500">({{ $employee->email }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('user_ids')" class="mt-2" />
                        </div>
                        
                        <div class="mb-4">
                            <x-input-label for="expiry_date" :value="__('Expiry Date (optional)')" />
                            <x-text-input id="expiry_date" class="block mt-1 w-full" type="date" name="expiry_date" :value="old('expiry_date')" />
                            <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('manager.benefits') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            
                            <x-primary-button>
                                {{ __('Assign Benefit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>