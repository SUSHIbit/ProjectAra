<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Members') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex">
                        <a href="{{ route('manager.members', ['status' => 'pending']) }}" class="mr-2 px-4 py-2 text-sm font-medium {{ $status === 'pending' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md">
                            Pending Approval
                        </a>
                        <a href="{{ route('manager.members', ['status' => 'approved']) }}" class="mr-2 px-4 py-2 text-sm font-medium {{ $status === 'approved' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md">
                            Approved Members
                        </a>
                        <a href="{{ route('manager.members', ['status' => 'all']) }}" class="px-4 py-2 text-sm font-medium {{ $status === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md">
                            All
                        </a>
                    </div>
                    
                    @if(count($members) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership Info</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($members as $member)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $member->user->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $member->user->email }}</div>
                                                <div class="text-sm text-gray-500">{{ $member->user->phone ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($member->is_approved)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @elseif($member->membership_requested)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($member->is_approved)
                                                    <div class="text-sm text-gray-900">
                                                        # {{ $member->membership_number }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Valid: {{ $member->membership_start_date->format('M d, Y') }} - {{ $member->membership_end_date->format('M d, Y') }}
                                                    </div>
                                                @elseif($member->membership_requested)
                                                    <div class="text-sm text-gray-500">
                                                        Requested: {{ $member->created_at->format('M d, Y') }}
                                                    </div>
                                                @else
                                                    <div class="text-sm text-gray-500">
                                                        N/A
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('manager.members.show', $member->id) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $members->links() }}
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-gray-700">No members found for the selected status.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>