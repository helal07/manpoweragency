@extends('layouts.applicant')

@section('title', 'My Profile')
@section('header_title', 'Profile Management')

@section('content')
<div class="space-y-8 max-w-5xl">
    
    <!-- Profile Picture & Resume Section -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Profile Assets</h3>
                <p class="text-xs text-slate-500">Upload your photograph and current resume/CV</p>
            </div>
        </div>
        
        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-8 items-start">
            @csrf
            @method('patch')
            <input type="hidden" name="update_assets" value="1">

            <!-- Avatar -->
            <div class="flex flex-col items-center gap-3">
                <div class="relative w-32 h-32 rounded-full border-4 border-slate-100 bg-slate-50 overflow-hidden flex items-center justify-center text-4xl font-bold text-slate-300 shadow-inner">
                    @if(auth()->user()->getFirstMediaUrl('avatar'))
                        <img src="{{ auth()->user()->getFirstMediaUrl('avatar') }}" class="w-full h-full object-cover" alt="Avatar">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="text-center">
                    <label for="avatar" class="cursor-pointer inline-block px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-colors">
                        Change Photo
                    </label>
                    <input type="file" id="avatar" name="avatar" class="hidden" accept="image/jpeg,image/png,image/webp">
                    <p class="text-xs text-slate-500 mt-2">JPG, PNG up to 2MB</p>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>
            </div>

            <div class="hidden sm:block w-px h-32 bg-slate-100"></div>

            <!-- Resume -->
            <div class="flex-1 w-full">
                <div class="mb-2">
                    <label class="block font-semibold text-slate-700 text-sm mb-1">Upload Resume / CV</label>
                    <p class="text-xs text-slate-500 mb-3">Upload your latest CV in PDF format (up to 5MB).</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <input type="file" name="resume" accept="application/pdf,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors border border-slate-200 rounded-lg">
                        <x-input-error class="mt-2" :messages="$errors->get('resume')" />
                    </div>
                </div>
                
                @if(auth()->user()->getFirstMediaUrl('resume'))
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg flex items-center justify-between border border-blue-100">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                            <div>
                                <div class="text-sm font-semibold text-slate-800">Current Resume Attached</div>
                                <div class="text-xs text-slate-500">Updated on {{ auth()->user()->getFirstMedia('resume')->updated_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <a href="{{ auth()->user()->getFirstMediaUrl('resume') }}" target="_blank" class="text-sm font-bold text-blue-600 hover:text-blue-800 bg-white px-3 py-1.5 rounded border border-blue-200 hover:bg-blue-50">View File</a>
                    </div>
                @endif
            </div>
            
            <div class="w-full sm:w-auto mt-4 sm:mt-0 flex flex-col justify-end h-full pt-10">
                <button type="submit" class="w-full px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-md shadow-blue-600/20">
                    Save Assets
                </button>
                @if (session('status') === 'assets-updated')
                    <p class="text-sm text-emerald-600 font-semibold mt-2 text-center" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                        Saved successfully.
                    </p>
                @endif
            </div>
        </form>
    </div>

    <!-- Comprehensive Profile Form -->
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('patch')

        <!-- 1. Personal & Contact Information -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Personal & Contact Details</h3>
                    <p class="text-xs text-slate-500">Essential identity and primary contact information</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                    <x-input-error class="mt-1" :messages="$errors->get('name')" />
                </div>

                <!-- Father's Name -->
                <div>
                    <label for="fathers_name" class="block text-sm font-semibold text-slate-700 mb-1">Father's Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="fathers_name" name="fathers_name" value="{{ old('fathers_name', $user->fathers_name) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Father's full name" required>
                    <x-input-error class="mt-1" :messages="$errors->get('fathers_name')" />
                </div>

                <!-- Mother's Name -->
                <div>
                    <label for="mothers_name" class="block text-sm font-semibold text-slate-700 mb-1">Mother's Name</label>
                    <input type="text" id="mothers_name" name="mothers_name" value="{{ old('mothers_name', $user->mothers_name) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Mother's full name">
                    <x-input-error class="mt-1" :messages="$errors->get('mothers_name')" />
                </div>

                <!-- Mobile No -->
                <div>
                    <label for="mobile_no" class="block text-sm font-semibold text-slate-700 mb-1">Primary Mobile No <span class="text-rose-500">*</span></label>
                    <input type="text" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $user->mobile_no ?? $user->phone) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="017xxxxxxxx" required>
                    <x-input-error class="mt-1" :messages="$errors->get('mobile_no')" />
                </div>

                <!-- Secondary Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">Alternative Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Optional telephone or second number">
                    <x-input-error class="mt-1" :messages="$errors->get('phone')" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                    <x-input-error class="mt-1" :messages="$errors->get('email')" />
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-semibold text-slate-700 mb-1">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    <x-input-error class="mt-1" :messages="$errors->get('date_of_birth')" />
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-semibold text-slate-700 mb-1">Gender</label>
                    <select id="gender" name="gender" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="">-- Select Gender --</option>
                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                        <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
                    </select>
                    <x-input-error class="mt-1" :messages="$errors->get('gender')" />
                </div>

                <!-- Marital Status -->
                <div>
                    <label for="marital_status" class="block text-sm font-semibold text-slate-700 mb-1">Marital Status</label>
                    <select id="marital_status" name="marital_status" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="">-- Select Status --</option>
                        <option value="single" @selected(old('marital_status', $user->marital_status) === 'single')>Single</option>
                        <option value="married" @selected(old('marital_status', $user->marital_status) === 'married')>Married</option>
                        <option value="divorced" @selected(old('marital_status', $user->marital_status) === 'divorced')>Divorced</option>
                        <option value="widowed" @selected(old('marital_status', $user->marital_status) === 'widowed')>Widowed</option>
                    </select>
                    <x-input-error class="mt-1" :messages="$errors->get('marital_status')" />
                </div>
            </div>
        </div>

        <!-- 2. Education Qualifications -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Education Details</h3>
                    <p class="text-xs text-slate-500">Academic qualifications, passing year and results</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Highest Education -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label for="highest_education" class="block text-sm font-semibold text-slate-700 mb-1">Highest Degree / Education</label>
                    <input type="text" id="highest_education" name="highest_education" value="{{ old('highest_education', $user->highest_education) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Bachelor of Arts, Diploma, SSC">
                    <x-input-error class="mt-1" :messages="$errors->get('highest_education')" />
                </div>

                <!-- SSC Year -->
                <div>
                    <label for="ssc_year" class="block text-sm font-semibold text-slate-700 mb-1">SSC Passing Year</label>
                    <input type="number" id="ssc_year" name="ssc_year" value="{{ old('ssc_year', $user->ssc_year) }}" min="1960" max="{{ date('Y') }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., 2018">
                    <x-input-error class="mt-1" :messages="$errors->get('ssc_year')" />
                </div>

                <!-- SSC Result -->
                <div>
                    <label for="ssc_result" class="block text-sm font-semibold text-slate-700 mb-1">SSC Result / GPA</label>
                    <input type="text" id="ssc_result" name="ssc_result" value="{{ old('ssc_result', $user->ssc_result) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., GPA 4.50 or 1st Div">
                    <x-input-error class="mt-1" :messages="$errors->get('ssc_result')" />
                </div>

                <!-- HSC Year -->
                <div>
                    <label for="hsc_year" class="block text-sm font-semibold text-slate-700 mb-1">HSC Passing Year</label>
                    <input type="number" id="hsc_year" name="hsc_year" value="{{ old('hsc_year', $user->hsc_year) }}" min="1960" max="{{ date('Y') }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., 2020">
                    <x-input-error class="mt-1" :messages="$errors->get('hsc_year')" />
                </div>

                <!-- HSC Result -->
                <div>
                    <label for="hsc_result" class="block text-sm font-semibold text-slate-700 mb-1">HSC Result / GPA</label>
                    <input type="text" id="hsc_result" name="hsc_result" value="{{ old('hsc_result', $user->hsc_result) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., GPA 4.20 or 1st Div">
                    <x-input-error class="mt-1" :messages="$errors->get('hsc_result')" />
                </div>
            </div>
        </div>

        <!-- 3. Work Experience & Skills -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Experience & Language Skills</h3>
                    <p class="text-xs text-slate-500">Your professional background and linguistic abilities</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Years of Experience -->
                <div>
                    <label for="experience_years" class="block text-sm font-semibold text-slate-700 mb-1">Total Experience (Years)</label>
                    <input type="number" id="experience_years" name="experience_years" value="{{ old('experience_years', $user->experience_years) }}" min="0" max="50" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g., 3">
                    <x-input-error class="mt-1" :messages="$errors->get('experience_years')" />
                </div>

                <!-- Can Speak English (Checkbox toggle) -->
                <div class="flex items-center pt-7">
                    <label class="relative flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="can_speak_english" value="1" @checked(old('can_speak_english', $user->can_speak_english)) class="w-5 h-5 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span class="text-sm font-semibold text-slate-700">Can speak in English</span>
                    </label>
                </div>

                <!-- English Proficiency Level -->
                <div>
                    <label for="english_proficiency" class="block text-sm font-semibold text-slate-700 mb-1">English Proficiency</label>
                    <select id="english_proficiency" name="english_proficiency" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">-- Select Level --</option>
                        <option value="basic" @selected(old('english_proficiency', $user->english_proficiency) === 'basic')>Basic</option>
                        <option value="conversational" @selected(old('english_proficiency', $user->english_proficiency) === 'conversational')>Conversational</option>
                        <option value="fluent" @selected(old('english_proficiency', $user->english_proficiency) === 'fluent')>Fluent</option>
                        <option value="native" @selected(old('english_proficiency', $user->english_proficiency) === 'native')>Native</option>
                    </select>
                    <x-input-error class="mt-1" :messages="$errors->get('english_proficiency')" />
                </div>

                <!-- Other Languages -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="other_languages" class="block text-sm font-semibold text-slate-700 mb-1">Other Known Languages</label>
                    <input type="text" id="other_languages" name="other_languages" value="{{ old('other_languages', $user->other_languages) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g., Arabic, Hindi, Malay, Japanese">
                    <x-input-error class="mt-1" :messages="$errors->get('other_languages')" />
                </div>

                <!-- Experience Details -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="experience_details" class="block text-sm font-semibold text-slate-700 mb-1">Experience Details</label>
                    <textarea id="experience_details" name="experience_details" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Describe previous overseas/domestic job roles, responsibilities, machinery operated, or trades practiced...">{{ old('experience_details', $user->experience_details) }}</textarea>
                    <x-input-error class="mt-1" :messages="$errors->get('experience_details')" />
                </div>
            </div>
        </div>

        <!-- 4. Passport & Travel Preferences -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Passport & Travel Information</h3>
                    <p class="text-xs text-slate-500">Government identity and preferred employment country</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- NID / Passport -->
                <div>
                    <label for="nid_passport" class="block text-sm font-semibold text-slate-700 mb-1">NID / Passport Number</label>
                    <input type="text" id="nid_passport" name="nid_passport" value="{{ old('nid_passport', $user->nid_passport) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="e.g., A12345678 or NID No">
                    <x-input-error class="mt-1" :messages="$errors->get('nid_passport')" />
                </div>

                <!-- Passport Expiry -->
                <div>
                    <label for="passport_expiry" class="block text-sm font-semibold text-slate-700 mb-1">Passport Expiry Date</label>
                    <input type="date" id="passport_expiry" name="passport_expiry" value="{{ old('passport_expiry', $user->passport_expiry?->format('Y-m-d')) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <x-input-error class="mt-1" :messages="$errors->get('passport_expiry')" />
                </div>

                <!-- Preferred Country -->
                <div>
                    <label for="preferred_country" class="block text-sm font-semibold text-slate-700 mb-1">Preferred Destination Country</label>
                    <input type="text" id="preferred_country" name="preferred_country" value="{{ old('preferred_country', $user->preferred_country) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="e.g., Saudi Arabia, UAE, Qatar">
                    <x-input-error class="mt-1" :messages="$errors->get('preferred_country')" />
                </div>

                <!-- LinkedIn -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="linkedin_url" class="block text-sm font-semibold text-slate-700 mb-1">LinkedIn / Professional Profile URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="https://linkedin.com/in/username">
                    <x-input-error class="mt-1" :messages="$errors->get('linkedin_url')" />
                </div>
            </div>
        </div>

        <!-- 5. Address & Emergency Contact -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Address & Emergency Contact</h3>
                    <p class="text-xs text-slate-500">Residential addresses and emergency contact person</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Address -->
                <div>
                    <label for="current_address" class="block text-sm font-semibold text-slate-700 mb-1">Current Present Address</label>
                    <textarea id="current_address" name="current_address" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-rose-500 focus:ring-rose-500" placeholder="House/Village, Road, Thana/Upazila, District...">{{ old('current_address', $user->current_address) }}</textarea>
                    <x-input-error class="mt-1" :messages="$errors->get('current_address')" />
                </div>

                <!-- Permanent Address -->
                <div>
                    <label for="permanent_address" class="block text-sm font-semibold text-slate-700 mb-1">Permanent Village Address</label>
                    <textarea id="permanent_address" name="permanent_address" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-rose-500 focus:ring-rose-500" placeholder="Permanent address as per NID/Passport...">{{ old('permanent_address', $user->permanent_address) }}</textarea>
                    <x-input-error class="mt-1" :messages="$errors->get('permanent_address')" />
                </div>

                <!-- Emergency Contact Name -->
                <div>
                    <label for="emergency_contact_name" class="block text-sm font-semibold text-slate-700 mb-1">Emergency Contact Person</label>
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-rose-500 focus:ring-rose-500" placeholder="Name of relative or guardian">
                    <x-input-error class="mt-1" :messages="$errors->get('emergency_contact_name')" />
                </div>

                <!-- Emergency Contact Phone -->
                <div>
                    <label for="emergency_contact_phone" class="block text-sm font-semibold text-slate-700 mb-1">Emergency Contact Mobile</label>
                    <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-rose-500 focus:ring-rose-500" placeholder="017xxxxxxxx">
                    <x-input-error class="mt-1" :messages="$errors->get('emergency_contact_phone')" />
                </div>
            </div>
        </div>

        <!-- 6. Dynamic Custom Fields Section (if configured by admin) -->
        @if(isset($customFields) && $customFields->isNotEmpty())
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Additional Information</h3>
                    <p class="text-xs text-slate-500">Custom questions requested for agency screening</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($customFields as $field)
                    @php
                        $fieldValue = old("custom_field.{$field->id}", $customFieldValues[$field->id] ?? null);
                    @endphp

                    <div class="{{ in_array($field->type, ['textarea', 'file']) ? 'md:col-span-2' : '' }}">
                        <label for="custom_field_{{ $field->id }}" class="block text-sm font-semibold text-slate-700 mb-1">
                            {{ $field->label }}
                            @if($field->is_required)
                                <span class="text-rose-500">*</span>
                            @endif
                        </label>

                        @if($field->type === 'textarea')
                            <textarea 
                                id="custom_field_{{ $field->id }}" 
                                name="custom_field[{{ $field->id }}]" 
                                rows="3" 
                                class="w-full border-slate-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                placeholder="{{ $field->placeholder ?? '' }}"
                                {{ $field->is_required ? 'required' : '' }}>{{ $fieldValue }}</textarea>

                        @elseif($field->type === 'select')
                            <select 
                                id="custom_field_{{ $field->id }}" 
                                name="custom_field[{{ $field->id }}]" 
                                class="w-full border-slate-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                {{ $field->is_required ? 'required' : '' }}>
                                <option value="">-- Select Option --</option>
                                @foreach($field->options ?? [] as $option)
                                    <option value="{{ $option }}" @selected($fieldValue == $option)>{{ $option }}</option>
                                @endforeach
                            </select>

                        @elseif($field->type === 'checkbox')
                            <div class="pt-2">
                                <label class="relative flex items-center gap-3 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="custom_field[{{ $field->id }}]" 
                                        value="1" 
                                        @checked($fieldValue == '1')
                                        class="w-5 h-5 text-teal-600 rounded border-slate-300 focus:ring-teal-500">
                                    <span class="text-sm font-medium text-slate-700">{{ $field->placeholder ?? 'Yes, I confirm this' }}</span>
                                </label>
                            </div>

                        @elseif($field->type === 'number')
                            <input 
                                type="number" 
                                id="custom_field_{{ $field->id }}" 
                                name="custom_field[{{ $field->id }}]" 
                                value="{{ $fieldValue }}" 
                                class="w-full border-slate-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                placeholder="{{ $field->placeholder ?? '' }}"
                                {{ $field->is_required ? 'required' : '' }}>

                        @elseif($field->type === 'date')
                            <input 
                                type="date" 
                                id="custom_field_{{ $field->id }}" 
                                name="custom_field[{{ $field->id }}]" 
                                value="{{ $fieldValue }}" 
                                class="w-full border-slate-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                {{ $field->is_required ? 'required' : '' }}>

                        @elseif($field->type === 'file')
                            <input 
                                type="file" 
                                id="custom_field_{{ $field->id }}" 
                                name="custom_field[{{ $field->id }}]" 
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-colors border border-slate-200 rounded-lg"
                                {{ $field->is_required && empty($fieldValue) ? 'required' : '' }}>
                            @if($fieldValue)
                                <p class="text-xs text-slate-500 mt-1">Current file uploaded: <a href="{{ asset('storage/' . $fieldValue) }}" target="_blank" class="text-teal-600 font-bold hover:underline">View Attached File</a></p>
                            @endif

                        @else
                            <input 
                                type="text" 
                                id="custom_field_{{ $field->id }}" 
                                name="custom_field[{{ $field->id }}]" 
                                value="{{ $fieldValue }}" 
                                class="w-full border-slate-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                placeholder="{{ $field->placeholder ?? '' }}"
                                {{ $field->is_required ? 'required' : '' }}>
                        @endif

                        @if($field->help_text)
                            <p class="text-xs text-slate-400 mt-1">{{ $field->help_text }}</p>
                        @endif

                        <x-input-error class="mt-1" :messages="$errors->get('custom_field.' . $field->id)" />
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Submit Bar -->
        <div class="flex items-center gap-4 pt-4 border-t border-slate-200">
            <button type="submit" class="px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-colors shadow-lg hover:shadow-xl transform active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save All Profile Information
            </button>
            @if (session('status') === 'profile-updated')
                <p class="text-sm text-emerald-600 font-semibold flex items-center gap-1.5" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Profile saved successfully.
                </p>
            @endif
        </div>
    </form>
</div>
@endsection
