@extends('layouts.app')

@section('page-title', 'Edit Membership Assignment')

@section('content')
<div class="py-6">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
    <div class="bg-white shadow rounded-lg p-6">
      <h1 class="text-xl font-semibold text-gray-900 mb-4">Edit Membership Assignment</h1>

      <div class="mb-4 text-sm text-gray-600">
        <p>Member: <span class="font-medium">{{ $memberMembershipPlan->member->name }}</span></p>
        <p>Plan: <span class="font-medium">{{ $memberMembershipPlan->membershipPlan->name }}</span></p>
      </div>

      <form method="POST" action="{{ route('member_membership_plans.update', $memberMembershipPlan) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
          <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $memberMembershipPlan->start_date) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
          @error('start_date')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
          <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $memberMembershipPlan->end_date) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
          @error('end_date')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
          <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @php $st = old('status', $memberMembershipPlan->status); @endphp
            <option value="active" {{ $st==='active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ $st==='expired' ? 'selected' : '' }}>Expired</option>
            <option value="cancelled" {{ $st==='cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
          @error('status')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center justify-end space-x-3 pt-2">
          <a href="{{ route('members.show', $memberMembershipPlan->member_id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
          <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">Save</button>
        </div>
      </form>
    </div>

    <div class="mt-6 bg-white shadow rounded-lg p-6">
      <h2 class="text-sm font-medium text-gray-900 mb-3">Danger zone</h2>
      <form method="POST" action="{{ route('member_membership_plans.destroy', $memberMembershipPlan) }}" onsubmit="return confirm('Remove this membership assignment? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">Remove Assignment</button>
      </form>
    </div>
  </div>
</div>
@endsection
