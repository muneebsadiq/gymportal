@extends('layouts.app')

@section('page-title', 'Members')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Page header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Members</h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('members.create') }}" class="btn-primary">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Member
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-6 bg-white shadow rounded-lg p-6">
            <form method="GET" action="{{ route('members.index') }}">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Name, ID, or phone">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Members list -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
            @if($members->count() > 0)
            <ul role="list" class="divide-y divide-gray-200">
                @foreach($members as $member)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                @if($member->profile_photo)
                                <img class="h-12 w-12 rounded-full" src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}">
                                @else
                                <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-700">{{ substr($member->name, 0, 1) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <p class="text-lg font-medium text-indigo-600 truncate">{{ $member->name }}</p>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($member->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                    @if($member->hasDueFees())
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Due Fees
                                    </span>
                                    @endif
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <p>{{ $member->member_id }} • {{ $member->phone }}</p>
                                    @if($member->activeMembership())
                                    <p class="ml-2">• {{ $member->activeMembership()->plan_name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($member->hasDueFees())
                            <a href="{{ route('payments.create', ['member_id' => $member->id]) }}" class="btn-danger btn-sm">
                                Collect Fee
                            </a>
                            @endif
                            <a href="{{ route('members.show', $member) }}" class="btn-secondary btn-sm">
                                View
                            </a>
                            <a href="{{ route('members.edit', $member) }}" class="btn-secondary btn-sm">Edit</a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                {{ $members->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No members found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding a new member.</p>
                <div class="mt-6">
                    <a href="{{ route('members.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Member
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
