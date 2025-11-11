@extends('layouts.app')

@section('page-title', 'Coaches')

@section('content')
<div class="py-6 overflow-x-hidden">
    <div class="max-w-full sm:max-w-4xl md:max-w-5xl lg:max-w-6xl xl:max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Coaches</h2>
                <p class="mt-1 text-sm text-gray-500">Manage gym coaches and their information</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('coaches.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Add Coach
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-8 bg-white shadow-lg rounded-lg p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Filters</h3>
            <form method="GET" action="{{ route('coaches.index') }}" class="space-y-4 sm:space-y-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div class="sm:col-span-2 lg:col-span-2 xl:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Coaches</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4" placeholder="Name, phone, or email">
                    </div>
                    <div class="sm:col-span-1 lg:col-span-1 xl:col-span-1">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-3 xl:col-span-1 flex items-end">
                        <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg border border-gray-200">
                @if($coaches->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed md:table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 md:px-4 md:w-auto">ID</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32 md:px-4 md:w-auto">Name</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40 md:px-4 md:w-auto">Contact</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 md:px-4 md:w-auto">Spec</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 md:px-4 md:w-auto">Salary</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 md:px-4 md:w-auto">Comm</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 md:px-4 md:w-auto">Status</th>
                                    <th class="px-2 py-3 w-24 md:px-4 md:w-auto"/>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($coaches as $coach)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm font-semibold text-indigo-600">{{ $coach->coach_id ?: '—' }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm font-medium text-gray-900 truncate max-w-32">{{ $coach->name }}</div>
                                        <div class="text-xs text-gray-500">{{ optional($coach->join_date)->format('M d') ?: '—' }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm text-gray-900">{{ $coach->phone ?: '—' }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-20 md:max-w-32">{{ $coach->email ?: '—' }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm text-gray-900 truncate max-w-24">{{ $coach->specialization ?: '—' }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm text-gray-900">{{ $coach->salary ? number_format($coach->salary, 0) : '—' }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm text-gray-900">{{ $coach->commission_rate ? $coach->commission_rate . '%' : '—' }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="flex flex-col space-y-0.5">
                                            <span class="inline-flex items-center px-1.5 py-0.5 md:px-2.5 rounded-full text-xs font-medium {{ $coach->status==='active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($coach->status) }}</span>
                                            @if($coach->salary && $coach->join_date && $coach->join_date->diffInDays(now()) >= 30)
                                                @if($coach->expenses->isEmpty())
                                                    <span class="inline-flex items-center px-1.5 py-0.5 md:px-2.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Due</span>
                                                @else
                                                    <span class="inline-flex items-center px-1.5 py-0.5 md:px-2.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-right text-xs md:text-sm font-medium md:px-4">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('coaches.show', $coach) }}" class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">View</a>
                                            <a href="{{ route('coaches.edit', $coach) }}" class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Edit</a>
                                            <form action="{{ route('coaches.destroy', $coach) }}" method="POST" class="inline" onsubmit="return confirm('Delete this coach?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-300 text-xs font-medium rounded-md text-red-700 bg-white hover:bg-red-50">Del</button>
                                            </form>
                                        </div>
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
                        @foreach($coaches as $coach)
                        <div class="p-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $coach->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $coach->coach_id ?: 'No ID' }} • {{ $coach->specialization ?: 'No spec' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-0.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $coach->status==='active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($coach->status) }}</span>
                                    @if($coach->salary && $coach->join_date && $coach->join_date->diffInDays(now()) >= 30)
                                        @if($coach->expenses->isEmpty())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Due</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-gray-500">Contact</p>
                                    <p class="font-medium">{{ $coach->phone ?: '—' }}</p>
                                    <p class="text-gray-500 text-xs truncate">{{ $coach->email ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Salary</p>
                                    <p class="font-medium">{{ $coach->salary ? 'PKR ' . number_format($coach->salary, 0) : '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Commission</p>
                                    <p class="font-medium">{{ $coach->commission_rate ? $coach->commission_rate . '%' : '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Join Date</p>
                                    <p class="font-medium">{{ optional($coach->join_date)->format('M d, Y') ?: '—' }}</p>
                                </div>
                            </div>

                            <div class="mt-3 flex space-x-2">
                                <a href="{{ route('coaches.show', $coach) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">View</a>
                                <a href="{{ route('coaches.edit', $coach) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Edit</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $coaches->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No coaches found</h3>
                    <p class="mt-1 text-sm text-gray-500">Add your first coach to get started.</p>
                    <div class="mt-6">
                        <a href="{{ route('coaches.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Add Coach
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


