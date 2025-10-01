@extends('layouts.app')

@section('page-title', 'Edit Settings')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Page header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Settings
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Update your gym's configuration
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('settings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- General Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">General Information</h3>
                </div>
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gym Logo</label>
                        @if($settings->logo)
                            <div class="mt-2 mb-4">
                                <img src="{{ asset('storage/' . $settings->logo) }}" alt="Current Logo" class="h-20 w-auto">
                                <p class="mt-1 text-xs text-gray-500">Current logo</p>
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB. Maximum dimensions: 400x400 pixels.</p>
                    </div>

                    <!-- Gym Name -->
                    <div>
                        <label for="gym_name" class="block text-sm font-medium text-gray-700">Gym Name *</label>
                        <input type="text" name="gym_name" id="gym_name" value="{{ old('gym_name', $settings->gym_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('gym_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="gym_email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="gym_email" id="gym_email" value="{{ old('gym_email', $settings->gym_email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('gym_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="gym_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="gym_phone" id="gym_phone" value="{{ old('gym_phone', $settings->gym_phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('gym_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="gym_address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="gym_address" id="gym_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('gym_address', $settings->gym_address) }}</textarea>
                        @error('gym_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- About -->
                    <div>
                        <label for="about" class="block text-sm font-medium text-gray-700">About</label>
                        <textarea name="about" id="about" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Brief description about your gym...">{{ old('about', $settings->about) }}</textarea>
                        @error('about')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Currency & Timezone -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Currency & Timezone</h3>
                </div>
                <div class="px-4 py-5 sm:p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700">Currency Code *</label>
                        <input type="text" name="currency" id="currency" value="{{ old('currency', $settings->currency) }}" required placeholder="PKR, USD, EUR" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('currency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Currency Symbol -->
                    <div>
                        <label for="currency_symbol" class="block text-sm font-medium text-gray-700">Currency Symbol *</label>
                        <input type="text" name="currency_symbol" id="currency_symbol" value="{{ old('currency_symbol', $settings->currency_symbol) }}" required placeholder="Rs, $, €" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('currency_symbol')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Timezone -->
                    <div class="sm:col-span-2">
                        <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone *</label>
                        <select name="timezone" id="timezone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Asia/Karachi" {{ old('timezone', $settings->timezone) == 'Asia/Karachi' ? 'selected' : '' }}>Asia/Karachi (PKT)</option>
                            <option value="Asia/Dubai" {{ old('timezone', $settings->timezone) == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                            <option value="America/New_York" {{ old('timezone', $settings->timezone) == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                            <option value="Europe/London" {{ old('timezone', $settings->timezone) == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                            <option value="Asia/Tokyo" {{ old('timezone', $settings->timezone) == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (JST)</option>
                        </select>
                        @error('timezone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Operating Hours -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Operating Hours</h3>
                </div>
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    <!-- Opening & Closing Time -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="opening_time" class="block text-sm font-medium text-gray-700">Opening Time *</label>
                            <input type="time" name="opening_time" id="opening_time" value="{{ old('opening_time', $settings->opening_time ? \Carbon\Carbon::parse($settings->opening_time)->format('H:i') : '06:00') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('opening_time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="closing_time" class="block text-sm font-medium text-gray-700">Closing Time *</label>
                            <input type="time" name="closing_time" id="closing_time" value="{{ old('closing_time', $settings->closing_time ? \Carbon\Carbon::parse($settings->closing_time)->format('H:i') : '22:00') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('closing_time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Working Days -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Working Days</label>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @php
                                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                $selectedDays = old('working_days', $settings->working_days ?? []);
                            @endphp
                            @foreach($days as $day)
                                <label class="flex items-center">
                                    <input type="checkbox" name="working_days[]" value="{{ $day }}" {{ in_array($day, $selectedDays) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">{{ ucfirst($day) }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('working_days')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <a href="{{ route('settings.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
