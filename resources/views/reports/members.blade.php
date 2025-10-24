@extends('layouts.app')

@section('page-title', 'Member Report')

@section('content')
<div class="py-6 overflow-x-hidden">
    <div class="max-w-full sm:max-w-4xl md:max-w-5xl lg:max-w-6xl xl:max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Member Report</h2>
                <p class="mt-1 text-sm text-gray-500">Comprehensive member analysis and statistics</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Reports
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 mb-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Filters</h3>
            <form method="GET" action="{{ route('reports.members') }}" class="space-y-4 sm:space-y-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div class="sm:col-span-2 lg:col-span-2 xl:col-span-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="sm:col-span-1 lg:col-span-1 xl:col-span-1">
                        <label for="joined_from" class="block text-sm font-medium text-gray-700 mb-2">Joined From</label>
                        <input type="date" name="joined_from" id="joined_from" value="{{ request('joined_from') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4">
                    </div>
                    <div class="sm:col-span-1 lg:col-span-1 xl:col-span-1 flex items-end">
                        <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('reports.members') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Members</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $members->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Members</dt>
                                <dd class="text-2xl font-bold text-green-600">{{ $members->where('status', 'active')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 sm:col-span-2 lg:col-span-1">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="text-xl sm:text-2xl font-bold text-gray-900">PKR {{ number_format($members->sum(fn($m) => $m->payments->sum('amount')), 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Table -->
        <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg border border-gray-200">
            @if($members->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed md:table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40 md:px-4 md:w-auto">Member</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32 md:px-4 md:w-auto">Contact</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 md:px-4 md:w-auto">Joined Date</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 md:px-4 md:w-auto">Status</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 md:px-4 md:w-auto">Total Paid</th>
                                <th class="px-2 py-3 w-16 md:px-4 md:w-auto"/>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($members as $member)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 md:h-10 md:w-10 flex-shrink-0">
                                            @if($member->profile_photo)
                                            <img class="h-8 w-8 md:h-10 md:w-10 rounded-full" src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}">
                                            @else
                                            <div class="h-8 w-8 md:h-10 md:w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-xs md:text-sm font-medium text-indigo-600">{{ substr($member->name, 0, 1) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="ml-3 md:ml-4">
                                            <div class="text-xs md:text-sm font-medium text-gray-900 truncate max-w-28">{{ $member->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $member->member_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                    <div class="text-xs md:text-sm text-gray-900">{{ $member->phone }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-24 md:max-w-32">{{ $member->email }}</div>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-xs md:text-sm text-gray-500 md:px-4">
                                    {{ $member->joined_date ? $member->joined_date->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                    <span class="inline-flex items-center px-1.5 py-0.5 md:px-2.5 rounded-full text-xs font-medium
                                        {{ $member->status === 'active' ? 'bg-green-100 text-green-800' :
                                           ($member->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-xs md:text-sm font-medium text-gray-900 md:px-4">
                                    PKR {{ number_format($member->payments->sum('amount'), 2) }}
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-right text-xs md:text-sm font-medium md:px-4">
                                    <a href="{{ route('members.show', $member) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($members as $member)
                    <div class="p-3 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-md flex items-center justify-center">
                                            @if($member->profile_photo)
                                            <img class="w-8 h-8 rounded-full" src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}">
                                            @else
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-xs font-medium text-indigo-600">{{ substr($member->name, 0, 1) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $member->member_id }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-0.5">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $member->status==='active' ? 'bg-green-100 text-green-800' : ($member->status==='inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($member->status) }}</span>
                            </div>
                        </div>

                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-gray-500">Contact</p>
                                <p class="font-medium">{{ $member->phone ?: '—' }}</p>
                                <p class="text-gray-500 text-xs truncate">{{ $member->email ?: '—' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Total Paid</p>
                                <p class="font-medium">PKR {{ number_format($member->payments->sum('amount'), 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Joined Date</p>
                                <p class="font-medium">{{ $member->joined_date ? $member->joined_date->format('M d, Y') : '—' }}</p>
                            </div>
                            <div class="text-right">
                                <div class="mt-3">
                                    <a href="{{ route('members.show', $member) }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $members->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No members found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
