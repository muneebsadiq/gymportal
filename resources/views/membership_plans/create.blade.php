@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-xl mx-auto px-4 sm:px-6 md:px-8">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl mb-6">Create Membership Plan</h2>
        <form action="{{ route('membership_plans.store') }}" method="POST" class="bg-white shadow-lg sm:rounded-xl p-5">
    @csrf
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input type="text" id="name" name="name" required
               class="block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 focus:outline-none transition placeholder-gray-400 py-2 px-3 text-base bg-gray-50" />
    </div>
    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 focus:outline-none transition placeholder-gray-400 py-2 px-3 text-base bg-gray-50"></textarea>
    </div>
    <div class="mb-4">
        <label for="fee" class="block text-sm font-medium text-gray-700 mb-1">Fee</label>
        <input type="number" step="0.01" id="fee" name="fee" required
               class="block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 focus:outline-none transition placeholder-gray-400 py-2 px-3 text-base bg-gray-50" />
    </div>
    <div class="mb-4">
        <label for="duration_type" class="block text-sm font-medium text-gray-700 mb-1">Duration Type</label>
        <select id="duration_type" name="duration_type" required
                class="block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 focus:outline-none transition py-2 px-3 text-base bg-gray-50">
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div>
    <div class="mb-4">
        <label for="duration_value" class="block text-sm font-medium text-gray-700 mb-1">Duration Value</label>
        <input type="number" id="duration_value" name="duration_value" required
               class="block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 focus:outline-none transition placeholder-gray-400 py-2 px-3 text-base bg-gray-50" />
    </div>
    <div class="mb-6">
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select id="status" name="status" required
                class="block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 focus:outline-none transition py-2 px-3 text-base bg-gray-50">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <div class="flex justify-end space-x-3">
        <button type="submit"
                class="inline-flex items-center px-5 py-2 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            Create Plan
        </button>
        <a href="{{ route('membership_plans.index') }}"
           class="inline-flex items-center px-5 py-2 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            Cancel
        </a>
    </div>
</form>
    </div>
</div>
@endsection