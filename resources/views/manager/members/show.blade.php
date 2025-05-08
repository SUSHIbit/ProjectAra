<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $member->user->name }}</h3>
                        
                        <div>
                            <a href="{{ route('manager.members') }}" class="text-indigo-600 hover:text-indigo-900">
                                &larr; Back to Members
                            </a>
                        </div>
                    </div>
                    
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-md font-medium text-gray-700 mb-3">User Information</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Name:</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->user->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Email:</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->user->email }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Phone:</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->user->phone ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Registered On:</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->user->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Membership Information</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                    <dd class="text-sm">
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
                                    </dd>
                                </div>
                                
                                @if($member->is_approved)
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Membership Number:</dt>
                                        <dd class="text-sm text-gray-900">{{ $member->membership_number }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Start Date:</dt>
                                        <dd class="text-sm text-gray-900">{{ $member->membership_start_date->format('M d, Y') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">End Date:</dt>
                                        <dd class="text-sm text-gray-900">{{ $member->membership_end_date->format('M d, Y') }}</dd>
                                    </div>
                                @endif
                                
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Membership Requested:</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    @if($member->notes)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-700 mb-2">Notes</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-900">{{ $member->notes }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($member->membership_requested && !$member->is_approved)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        This membership request is pending your approval.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-4">
                            <form method="POST" action="{{ route('manager.members.approve', $member->id) }}" class="w-1/2">
                                @csrf
                                <div class="mb-4">
                                    <x-input-label for="approve_notes" :value="__('Approval Notes (optional)')" />
                                    <textarea id="approve_notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Approve Membership
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('manager.members.reject', $member->id) }}" class="w-1/2">
                                @csrf
                                <div class="mb-4">
                                    <x-input-label for="reject_notes" :value="__('Rejection Notes (optional)')" />
                                    <textarea id="reject_notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Reject Membership
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>