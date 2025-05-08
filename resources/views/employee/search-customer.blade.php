<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Customer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('employee.search-customer.post') }}">
                        @csrf
                        <div class="flex">
                            <div class="flex-grow">
                                <x-text-input
                                    type="text"
                                    name="search"
                                    id="search"
                                    class="w-full"
                                    placeholder="Search by name, email, or phone"
                                    value="{{ $search ?? '' }}"
                                    required
                                    autofocus
                                />
                                <x-input-error :messages="$errors->get('search')" class="mt-2" />
                            </div>
                            <div class="ml-4">
                                <x-primary-button>
                                    {{ __('Search') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                    
                    @if(isset($customers))
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Search Results</h3>
                            
                            @if(count($customers) > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($customers as $customer)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                                                        <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($customer->member)
                                                            @if($customer->member->is_approved)
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                    Active Member
                                                                </span>
                                                                <div class="text-sm text-gray-500 mt-1">
                                                                    #{{ $customer->member->membership_number }}
                                                                </div>
                                                            @else
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                    Pending Approval
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                Non-Member
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('employee.customer-service', ['customer_id' => $customer->id]) }}" class="text-indigo-600 hover:text-indigo-900">Create Service</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <p class="text-gray-700">No customers found for "{{ $search }}".</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>