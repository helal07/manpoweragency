@extends('layouts.applicant')

@section('title', 'My Profile')
@section('header_title', 'Profile Management')

@section('content')
<div class="space-y-6 max-w-4xl">
    
    <!-- Profile Picture & Resume Section -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Profile Assets</h3>
        
        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-8 items-start">
            @csrf
            @method('patch')
            <input type="hidden" name="update_assets" value="1">

            <!-- Avatar -->
            <div class="flex flex-col items-center gap-3">
                <div class="relative w-32 h-32 rounded-full border-4 border-slate-100 bg-slate-50 overflow-hidden flex items-center justify-center text-4xl font-bold text-slate-300">
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
                </div>
            </div>

            <div class="hidden sm:block w-px h-32 bg-slate-100"></div>

            <!-- Resume -->
            <div class="flex-1 w-full">
                <div class="mb-2">
                    <label class="block font-semibold text-slate-700 text-sm mb-1">Upload Resume / CV</label>
                    <p class="text-xs text-slate-500 mb-3">Upload your latest resume. PDF format is highly recommended.</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <input type="file" name="resume" accept="application/pdf,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors border border-slate-200 rounded-lg">
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
            </div>
        </form>
    </div>

    <!-- Personal & Address Info -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-1">Personal Details</h3>
        <p class="text-sm text-slate-500 mb-6">Update your contact information and addresses.</p>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>

                <!-- LinkedIn -->
                <div>
                    <label for="linkedin_url" class="block text-sm font-semibold text-slate-700 mb-1">LinkedIn Profile URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Address -->
                <div>
                    <label for="current_address" class="block text-sm font-semibold text-slate-700 mb-1">Current Address</label>
                    <textarea id="current_address" name="current_address" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('current_address', $user->current_address) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('current_address')" />
                </div>

                <!-- Permanent Address -->
                <div>
                    <label for="permanent_address" class="block text-sm font-semibold text-slate-700 mb-1">Permanent Address</label>
                    <textarea id="permanent_address" name="permanent_address" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('permanent_address', $user->permanent_address) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('permanent_address')" />
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-colors shadow-md">
                    Save Changes
                </button>
                @if (session('status') === 'profile-updated')
                    <p class="text-sm text-emerald-600 font-semibold flex items-center gap-1" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Saved.
                    </p>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
