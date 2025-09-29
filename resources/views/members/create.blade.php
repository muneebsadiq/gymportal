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
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Membership Plan</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="membership_plan_id" class="block text-sm font-medium text-gray-700">Select Plan <span class="text-red-600">*</span></label>
                            <select name="membership_plan_id" id="membership_plan_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}"
                                            data-fee="{{ $plan->fee }}"
                                            data-duration-value="{{ $plan->duration_value }}"
                                            data-duration-type="{{ $plan->duration_type }}"
                                            data-description="{{ $plan->description }}"
                                            {{ old('membership_plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} ({{ $plan->duration_value }} {{ ucfirst($plan->duration_type) }}, ₹{{ number_format($plan->fee, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('membership_plan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="plan_info" class="hidden rounded-md border border-gray-200 bg-gray-50 p-4 text-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-gray-900 font-medium" id="plan_info_name">Selected Plan</div>
                                    <div class="text-gray-600" id="plan_info_desc"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-gray-900 font-semibold">₹<span id="plan_info_fee">0</span></div>
                                    <div class="text-gray-600"><span id="plan_info_duration_value">0</span> <span id="plan_info_duration_type">months</span></div>
                                </div>
                            </div>
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

  const planSelect = document.getElementById('membership_plan_id');
  const infoBox = document.getElementById('plan_info');
  const infoName = document.getElementById('plan_info_name');
  const infoDesc = document.getElementById('plan_info_desc');
  const infoFee = document.getElementById('plan_info_fee');
  const infoDurVal = document.getElementById('plan_info_duration_value');
  const infoDurType = document.getElementById('plan_info_duration_type');

  function refreshPlanInfo() {
    const opt = planSelect.options[planSelect.selectedIndex];
    if (!opt || !opt.value) {
      infoBox.classList.add('hidden');
      return;
    }
    infoName.textContent = opt.text.split('(')[0].trim();
    infoDesc.textContent = opt.getAttribute('data-description') || '';
    infoFee.textContent = (opt.getAttribute('data-fee') || '0');
    infoDurVal.textContent = (opt.getAttribute('data-duration-value') || '0');
    infoDurType.textContent = (opt.getAttribute('data-duration-type') || '').toLowerCase();
    infoBox.classList.remove('hidden');
  }

  if (planSelect) {
    planSelect.addEventListener('change', refreshPlanInfo);
    refreshPlanInfo();
  }
});
</script>
@endpush
