<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload QR Code') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Employee: {{ $user->name }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                    
                    @if($user->qr_code_path)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-700 mb-2">Current QR Code</h4>
                            <div class="max-w-xs">
                                <img src="{{ Storage::url($user->qr_code_path) }}" alt="QR Code" class="w-full border rounded-lg">
                            </div>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('manager.employees.upload-qr', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="qr_image" :value="__('Upload QR Code Image')" />
                            <input type="file" id="qr_image" name="qr_image" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100" required accept="image/*">
                            <p class="mt-1 text-xs text-gray-500">Accepted file types: JPG, PNG, GIF. Maximum size: 2MB.</p>
                            <x-input-error :messages="$errors->get('qr_image')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('manager.employees') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            
                            <x-primary-button>
                                {{ $user->qr_code_path ? 'Update QR Code' : 'Upload QR Code' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>