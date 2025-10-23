@extends('layouts.app')

@section('page-title', 'Add New Member')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Add New Member</h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Members
                </a>
            </div>
        </div>

        @if ($errors->any())
        <div class="mt-6 bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
            <div class="font-medium">Please fix the following errors:</div>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data" class="mt-6">
            @csrf
            
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <!-- Personal Information -->
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-600">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-red-600">*</span></label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="e.g. 0300-1234567" pattern="[0-9\-\+\s]{7,20}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Use digits only or include dashes/spaces.</p>
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="gender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Joined Date -->
                        <div>
                            <label for="joined_date" class="block text-sm font-medium text-gray-700">Joining Date <span class="text-red-600">*</span></label>
                            <input type="date" name="joined_date" id="joined_date" value="{{ old('joined_date', date('Y-m-d')) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('joined_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" id="address" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emergency Contact -->
                    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('emergency_contact')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emergency_phone" class="block text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                            <input type="text" name="emergency_phone" id="emergency_phone" value="{{ old('emergency_phone') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('emergency_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Medical Conditions -->
                    <div class="mt-6">
                        <label for="medical_conditions" class="block text-sm font-medium text-gray-700">Medical Conditions / Notes</label>
                        <textarea name="medical_conditions" id="medical_conditions" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('medical_conditions') }}</textarea>
                        @error('medical_conditions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Profile Photo -->
                    <div class="mt-6">
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700">Profile Photo</label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <div class="mt-3">
                            <img id="profile_preview" src="#" alt="Preview" class="hidden h-20 w-20 rounded-full object-cover ring-1 ring-gray-200" />
                        </div>
                        @error('profile_photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Membership Plan Assignment -->
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Membership Plan & Coach</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Plan Name -->
                        <div>
                            <label for="plan_name" class="block text-sm font-medium text-gray-700">Plan Name <span class="text-red-600">*</span></label>
                            <input type="text" name="plan_name" id="plan_name" value="{{ old('plan_name') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('plan_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Plan Description -->
                        <div>
                            <label for="plan_description" class="block text-sm font-medium text-gray-700">Plan Description</label>
                            <textarea name="plan_description" id="plan_description" rows="2" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('plan_description') }}</textarea>
                            @error('plan_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Monthly Fee & Duration -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="monthly_fee" class="block text-sm font-medium text-gray-700">Monthly Fee <span class="text-red-600">*</span></label>
                                <input type="number" step="0.01" name="monthly_fee" id="monthly_fee" value="{{ old('monthly_fee') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('monthly_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duration_months" class="block text-sm font-medium text-gray-700">Duration (Months) <span class="text-red-600">*</span></label>
                                <input type="number" name="duration_months" id="duration_months" value="{{ old('duration_months', 1) }}" min="1" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('duration_months')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="coach_id" class="block text-sm font-medium text-gray-700">Assign Coach</label>
                            <select name="coach_id" id="coach_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">No Coach</option>
                                @php($coaches = \App\Models\Coach::orderBy('name')->get())
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>{{ $coach->name }} {{ $coach->specialization ? '(' . $coach->specialization . ')' : '' }}</option>
                                @endforeach
                            </select>
                            @error('coach_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Member
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('profile_photo');
  const preview = document.getElementById('profile_preview');
  if (input) {
    input.addEventListener('change', function (e) {
      const file = e.target.files && e.target.files[0];
      if (file) {
        const url = URL.createObjectURL(file);
        preview.src = url;
        preview.classList.remove('hidden');
      } else {
        preview.src = '#';
        preview.classList.add('hidden');
      }
    });
  }
});
</script>
@endpush
