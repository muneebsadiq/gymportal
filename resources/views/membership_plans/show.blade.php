@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Membership Plan Details</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $plan->name }}</h4>
            <p class="card-text"><strong>Description:</strong> {{ $plan->description }}</p>
            <p class="card-text"><strong>Fee:</strong> {{ $plan->fee }}</p>
            <p class="card-text"><strong>Duration:</strong> {{ ucfirst($plan->duration_type) }} ({{ $plan->duration_value }})</p>
            <p class="card-text"><strong>Status:</strong> {{ ucfirst($plan->status) }}</p>
            <a href="{{ route('membership_plans.edit', $plan->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('membership_plans.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
