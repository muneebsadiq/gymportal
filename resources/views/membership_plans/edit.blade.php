@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Membership Plan</h2>
    <form action="{{ route('membership_plans.update', $membershipPlan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $membershipPlan->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $membershipPlan->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="fee" class="form-label">Fee</label>
            <input type="number" step="0.01" class="form-control" id="fee" name="fee" value="{{ $membershipPlan->fee }}" required>
        </div>
        <div class="mb-3">
            <label for="duration_type" class="form-label">Duration Type</label>
            <select class="form-control" id="duration_type" name="duration_type" required>
                <option value="monthly" {{ $membershipPlan->duration_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ $membershipPlan->duration_type == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="duration_value" class="form-label">Duration Value</label>
            <input type="number" class="form-control" id="duration_value" name="duration_value" value="{{ $membershipPlan->duration_value }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active" {{ $membershipPlan->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $membershipPlan->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Plan</button>
        <a href="{{ route('membership_plans.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
