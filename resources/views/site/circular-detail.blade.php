@extends('layouts.site')

@section('title', $circular->title . ' | Job Circular Details')

@section('content')
<!-- Header Banner -->
<div class="bg-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('circulars.index') }}" class="text-xs font-bold text-blue-400 hover:text-blue-300 flex items-center gap-1">
                &larr; Back to Job Circulars
            </a>
            <span class="text-slate-600">•</span>
            <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30 uppercase">
                {{ $circular->country }}
            </span>
        </div>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-white">{{ $circular->title }}</h1>
        <div class="mt-4 flex flex-wrap items-center gap-6 text-sm text-slate-300">
            <span>Category: <strong class="text-white">{{ $circular->category }}</strong></span>
            <span>Vacancies: <strong class="text-white">{{ $circular->vacancy }} Positions</strong></span>
            <span>Posted: <strong class="text-white">{{ $circular->posted_at ? $circular->posted_at->format('M d, Y') : 'Recently' }}</strong></span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" x-data="{ applyModalOpen: {{ $errors->any() ? 'true' : 'false' }} }">
    <div class="grid lg:grid-cols-12 gap-12">
        <div class="lg:col-span-8 space-y-8">
            {{-- Featured Image --}}
            @if($circular->hasMedia('circular-image'))
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <img
                        src="{{ $circular->getFirstMediaUrl('circular-image') }}"
                        alt="{{ $circular->title }}"
                        class="w-full h-auto max-h-[600px] object-contain bg-slate-50"
                        loading="lazy"
                    >
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-3">Job Description</h2>
                    <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $circular->description }}</p>
                </div>

                @if($circular->requirements)
                    <div class="pt-6 border-t border-slate-100">
                        <h2 class="text-xl font-bold text-slate-900 mb-3">Candidate Requirements & Qualifications</h2>
                        <div class="text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50 p-6 rounded-xl border border-slate-200 text-sm">
                            {{ $circular->requirements }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Circular-Specific Application Requirements Box --}}
            @if($circular->customFields->count() > 0)
                <div class="bg-gradient-to-br from-blue-50/70 to-indigo-50/50 rounded-2xl border border-blue-100 p-8 shadow-sm space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Required Application Documents & Information</h2>
                            <p class="text-xs text-slate-500">Please prepare the following requirements to complete your application for this position.</p>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3 pt-2">
                        @foreach($circular->customFields as $field)
                            @php $isReq = (bool) ($field->pivot->is_required ?? false); @endphp
                            <div class="bg-white p-4 rounded-xl border border-blue-100 flex items-start justify-between gap-3 shadow-xs">
                                <div>
                                    <div class="font-semibold text-slate-900 text-sm flex items-center gap-1.5">
                                        @if($field->type === 'file')
                                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        @endif
                                        <span>{{ $field->label }}</span>
                                    </div>
                                    @if($field->help_text)
                                        <p class="text-xs text-slate-500 mt-1">{{ $field->help_text }}</p>
                                    @endif
                                </div>
                                <span class="shrink-0 px-2 py-0.5 rounded text-[11px] font-bold {{ $isReq ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $isReq ? 'Mandatory' : 'Optional' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Attachments (PDFs / additional images) --}}
            @if($circular->getMedia('circular-attachments')->count())
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        Attachments & Official Documents
                    </h2>
                    <div class="space-y-4">
                        @foreach($circular->getMedia('circular-attachments') as $attachment)
                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                <div class="rounded-xl overflow-hidden border border-slate-200">
                                    <a href="{{ $attachment->getUrl() }}" target="_blank">
                                        <img src="{{ $attachment->getUrl() }}" alt="{{ $attachment->file_name }}" class="w-full h-auto max-h-[500px] object-contain bg-slate-50" loading="lazy">
                                    </a>
                                    <div class="px-4 py-2 bg-slate-50 border-t border-slate-200 text-xs text-slate-500">
                                        {{ $attachment->file_name }} — <a href="{{ $attachment->getUrl() }}" target="_blank" class="text-blue-600 hover:underline">Open Full Size</a>
                                    </div>
                                </div>
                            @else
                                <a href="{{ $attachment->getUrl() }}" target="_blank" class="flex items-center gap-3 px-5 py-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-300 transition-colors group">
                                    <svg class="w-8 h-8 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                                    <div>
                                        <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700">{{ $attachment->file_name }}</span>
                                        <span class="block text-[11px] text-slate-400 mt-0.5">{{ strtoupper(pathinfo($attachment->file_name, PATHINFO_EXTENSION)) }} Document · {{ number_format($attachment->size / 1024, 1) }} KB — Click to view / download</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Circular Overview</h3>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Salary Range:</span>
                        <span class="font-bold text-slate-900">{{ $circular->salary_range }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Destination:</span>
                        <span class="font-bold text-slate-900">{{ $circular->country }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Total Vacancies:</span>
                        <span class="font-bold text-slate-900">{{ $circular->vacancy }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Deadline:</span>
                        <span class="font-bold text-rose-600">{{ $circular->deadline ? $circular->deadline->format('M d, Y') : 'Open' }}</span>
                    </div>
                </div>

                <div class="pt-4 space-y-3">
                    @auth('web')
                        @php
                            $hasApplied = \App\Models\JobApplication::where('applicant_id', auth()->id())->where('job_circular_id', $circular->id)->exists();
                            $hasSaved = \App\Models\SavedJob::where('applicant_id', auth()->id())->where('job_circular_id', $circular->id)->exists();
                        @endphp

                        @if (session('success'))
                            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-semibold">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($hasApplied)
                            <div class="w-full py-3.5 text-center font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl cursor-default flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Already Applied
                            </div>
                            <div class="text-center">
                                <a href="{{ route('applications.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                    View Application Status &rarr;
                                </a>
                            </div>
                        @else
                            <button
                                type="button"
                                @click="applyModalOpen = true"
                                class="w-full py-3.5 text-center font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-md flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Apply Now
                            </button>
                        @endif

                        <div class="pt-2">
                            @if ($hasSaved)
                                <form action="{{ route('saved-jobs.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="job_circular_id" value="{{ $circular->id }}">
                                    <button type="submit" class="w-full py-2.5 text-center font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors flex justify-center items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                        Saved
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('saved-jobs.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="job_circular_id" value="{{ $circular->id }}">
                                    <button type="submit" class="w-full py-2.5 text-center font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors flex justify-center items-center gap-2">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                        Save Job
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-3.5 text-center font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-md">
                            Login to Apply
                        </a>
                        <p class="text-[11px] text-center text-slate-400 mt-2">New job seeker? <a href="{{ route('register') }}" class="text-blue-600 underline">Register Account</a></p>
                    @endauth
                </div>
            </div>

            @if(!empty($siteSettings['show_bmet_license']) && !empty($siteSettings['bmet_license_no']))
            <div class="bg-slate-900 text-white rounded-2xl p-6 border border-slate-800 text-xs space-y-3">
                <div class="font-bold text-blue-400 uppercase tracking-wider">Government License Notice</div>
                <p class="text-slate-300 leading-relaxed">This job circular is issued under BMET Govt. License {{ $siteSettings['bmet_license_no'] }}. All selected candidates receive official employment contracts and BMET clearance prior to flight departure.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Application Modal --}}
    @auth('web')
        @php
            $applicant = auth()->user();
            $profileChecklist = [
                'fathers_name' => "Father's Name",
                'mobile_no' => 'Mobile Number',
                'current_address' => 'Present Address',
                'permanent_address' => 'Permanent Address',
                'nid_passport' => 'NID / Passport No',
            ];
            $missingProfileFields = [];
            foreach ($profileChecklist as $fKey => $fLabel) {
                if (empty($applicant->{$fKey})) {
                    if ($fKey === 'mobile_no' && !empty($applicant->phone)) {
                        continue;
                    }
                    $missingProfileFields[] = $fLabel;
                }
            }
            $isProfileComplete = empty($missingProfileFields);
            $totalFieldsCount = count($profileChecklist);
            $filledFieldsCount = $totalFieldsCount - count($missingProfileFields);
            $profilePercent = round(($filledFieldsCount / $totalFieldsCount) * 100);
        @endphp

        <div
            x-show="applyModalOpen"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto overscroll-contain"
            style="display: none;"
            @keydown.escape.window="applyModalOpen = false"
            role="dialog"
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div
                x-show="applyModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs transition-opacity"
                @click="applyModalOpen = false"
            ></div>

            {{-- Dialog Container: sleek, compact, and perfectly centered --}}
            <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center sm:text-left">
                <div
                    x-show="applyModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    class="relative bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl w-full max-w-xl my-0 sm:my-8 border border-slate-200/80 flex flex-col max-h-[92vh] sm:max-h-[88vh] overflow-hidden text-left"
                    @click.stop
                >
                    {{-- Executive Navy/Indigo Header --}}
                    <div class="sticky top-0 z-20 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white px-5 py-4 sm:px-6 sm:py-5 flex items-start justify-between border-b border-slate-800 shadow-md">
                        <div class="pr-2 space-y-1">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/20 text-blue-300 border border-blue-400/30">
                                <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Official Job Application</span>
                            </div>
                            <h2 class="text-base sm:text-lg font-extrabold text-white leading-snug">{{ $circular->title }}</h2>
                            <div class="flex items-center flex-wrap gap-2 text-xs text-slate-300 pt-0.5">
                                <span class="inline-flex items-center gap-1 font-semibold text-emerald-400 bg-emerald-950/60 px-2 py-0.5 rounded-md border border-emerald-500/30">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $circular->country }}
                                </span>
                                <span class="text-slate-400 font-medium">Salary: <strong class="text-white">{{ $circular->salary_range }}</strong></span>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="applyModalOpen = false"
                            class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition-colors shrink-0 cursor-pointer"
                            aria-label="Close"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Form with scrollable body and sticky action bar --}}
                    <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        <input type="hidden" name="job_circular_id" value="{{ $circular->id }}">

                        {{-- Smoothly Scrollable Body --}}
                        <div class="overflow-y-auto flex-1 px-5 py-5 sm:px-6 sm:py-6 space-y-5 overscroll-contain bg-slate-50/40" style="-webkit-overflow-scrolling: touch;">

                            @if(!$isProfileComplete)
                                {{-- Lucrative Profile Alert & Progress Card --}}
                                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-amber-300 shadow-sm border-l-4 border-l-amber-500 space-y-3.5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 font-bold">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-bold text-slate-900">Profile Incomplete</h3>
                                                <p class="text-xs text-slate-500">100% verified profile required to apply</p>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                            {{ $profilePercent }}% Complete
                                        </span>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-full rounded-full transition-all duration-500" style="width: {{ $profilePercent }}%"></div>
                                    </div>

                                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-200/80 space-y-2">
                                        <div class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Required fields missing from your profile:</div>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($missingProfileFields as $field)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                    <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    {{ $field }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <a href="{{ route('profile.edit') }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-bold transition-all shadow-md shadow-blue-600/20 hover:shadow-lg">
                                        <span>Complete Your Profile Now</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            @endif

                            {{-- Applicant Information Summary (Micro-Cards Layout) --}}
                            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs space-y-3.5">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wide">Applicant Information Record</h3>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold text-xs inline-flex items-center gap-1 hover:underline">
                                        Edit &rarr;
                                    </a>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                                    <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Applicant Name</span>
                                        <span class="font-bold text-slate-900 text-xs sm:text-sm">{{ $applicant->name }}</span>
                                    </div>

                                    <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Father's Name</span>
                                        @if($applicant->fathers_name)
                                            <span class="font-semibold text-slate-800">{{ $applicant->fathers_name }}</span>
                                        @else
                                            <span class="text-rose-500 font-medium italic">Missing</span>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Mother's Name</span>
                                        <span class="font-medium text-slate-700">{{ $applicant->mothers_name ?: 'Not provided' }}</span>
                                    </div>

                                    <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Mobile Number</span>
                                        <span class="font-semibold text-slate-800">{{ $applicant->mobile_no ?: ($applicant->phone ?: 'Not provided') }}</span>
                                    </div>

                                    <div class="sm:col-span-2 bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Present Address</span>
                                        @if($applicant->current_address)
                                            <span class="font-semibold text-slate-800 leading-relaxed">{{ $applicant->current_address }}</span>
                                        @else
                                            <span class="text-rose-500 font-medium italic">Missing</span>
                                        @endif
                                    </div>

                                    <div class="sm:col-span-2 bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Permanent Address</span>
                                        @if($applicant->permanent_address)
                                            <span class="font-semibold text-slate-800 leading-relaxed">{{ $applicant->permanent_address }}</span>
                                        @else
                                            <span class="text-rose-500 font-medium italic">Missing</span>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Email</span>
                                        <span class="font-semibold text-slate-800 truncate block">{{ $applicant->email }}</span>
                                    </div>

                                    <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">NID / Passport No</span>
                                        @if($applicant->nid_passport)
                                            <span class="font-semibold text-slate-800">{{ $applicant->nid_passport }}</span>
                                        @else
                                            <span class="text-rose-500 font-medium italic">Missing</span>
                                        @endif
                                    </div>

                                    <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Date of Birth &amp; Gender</span>
                                        <span class="font-medium text-slate-800">
                                            {{ $applicant->date_of_birth ? $applicant->date_of_birth->format('d M, Y') : 'N/A' }} 
                                            ({{ ucfirst($applicant->gender ?: 'N/A') }})
                                        </span>
                                    </div>

                                    <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Master Resume / CV</span>
                                        @if($applicant->getFirstMediaUrl('resume'))
                                            <a href="{{ $applicant->getFirstMediaUrl('resume') }}" target="_blank" class="text-blue-600 font-bold hover:underline inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                <span>View Attached CV</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">No resume attached</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Position Specific Requirements & Documents (Dynamic Custom Fields) --}}
                            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs space-y-4">
                                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wide">Position Requirements &amp; Documents</h3>
                                        <p class="text-[11px] text-slate-500">Specific requirements for this circular</p>
                                    </div>
                                </div>

                                @if($circular->customFields->count() > 0)
                                    <div class="space-y-3.5">
                                        @foreach($circular->customFields as $field)
                                            @php
                                                $isRequired = (bool) ($field->pivot->is_required ?? false);
                                                $fieldKey = "custom_fields.{$field->id}";
                                                $oldVal = old("custom_fields.{$field->id}");
                                            @endphp

                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-800">
                                                    {{ $field->label }}
                                                    @if($isRequired)
                                                        <span class="text-rose-500 font-extrabold">*</span>
                                                    @else
                                                        <span class="text-slate-400 font-normal">(Optional)</span>
                                                    @endif
                                                </label>

                                                @if($field->help_text)
                                                    <p class="text-[11px] text-slate-500">{{ $field->help_text }}</p>
                                                @endif

                                                @if($field->type === 'text')
                                                    <input
                                                        type="text"
                                                        name="custom_fields[{{ $field->id }}]"
                                                        value="{{ $oldVal }}"
                                                        placeholder="{{ $field->placeholder ?? 'Enter ' . $field->label }}"
                                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error($fieldKey) border-rose-500 @enderror"
                                                        {{ $isRequired ? 'required' : '' }}
                                                    >
                                                @elseif($field->type === 'textarea')
                                                    <textarea
                                                        name="custom_fields[{{ $field->id }}]"
                                                        rows="3"
                                                        placeholder="{{ $field->placeholder ?? 'Enter ' . $field->label }}"
                                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error($fieldKey) border-rose-500 @enderror"
                                                        {{ $isRequired ? 'required' : '' }}
                                                    >{{ $oldVal }}</textarea>
                                                @elseif($field->type === 'number')
                                                    <input
                                                        type="number"
                                                        name="custom_fields[{{ $field->id }}]"
                                                        value="{{ $oldVal }}"
                                                        placeholder="{{ $field->placeholder ?? '0' }}"
                                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error($fieldKey) border-rose-500 @enderror"
                                                        {{ $isRequired ? 'required' : '' }}
                                                    >
                                                @elseif($field->type === 'date')
                                                    <input
                                                        type="date"
                                                        name="custom_fields[{{ $field->id }}]"
                                                        value="{{ $oldVal }}"
                                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error($fieldKey) border-rose-500 @enderror"
                                                        {{ $isRequired ? 'required' : '' }}
                                                    >
                                                @elseif($field->type === 'select')
                                                    <select
                                                        name="custom_fields[{{ $field->id }}]"
                                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error($fieldKey) border-rose-500 @enderror"
                                                        {{ $isRequired ? 'required' : '' }}
                                                    >
                                                        <option value="">-- Select option --</option>
                                                        @if(is_array($field->options))
                                                            @foreach($field->options as $opt)
                                                                <option value="{{ $opt }}" {{ $oldVal === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                @elseif($field->type === 'checkbox')
                                                    <label class="inline-flex items-center gap-2 cursor-pointer mt-1">
                                                        <input
                                                            type="checkbox"
                                                            name="custom_fields[{{ $field->id }}]"
                                                            value="1"
                                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4"
                                                            {{ $oldVal ? 'checked' : '' }}
                                                        >
                                                        <span class="text-xs font-semibold text-slate-700">Yes / Confirm requirement</span>
                                                    </label>
                                                @elseif($field->type === 'file')
                                                    <div class="mt-1">
                                                        <input
                                                            type="file"
                                                            name="custom_fields[{{ $field->id }}]"
                                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp"
                                                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer border border-slate-200 rounded-xl p-1 bg-slate-50/50 @error($fieldKey) border-rose-500 @enderror"
                                                            {{ $isRequired ? 'required' : '' }}
                                                        >
                                                        <p class="text-[10px] text-slate-400 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                                                    </div>
                                                @endif

                                                @error($fieldKey)
                                                    <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>No extra circular-specific documents required. Your verified profile credentials and resume will be submitted.</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Cover Letter / Remarks --}}
                            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs space-y-2">
                                <label class="block text-xs font-bold text-slate-800">
                                    Cover Letter / Remarks <span class="text-slate-400 font-normal">(Optional)</span>
                                </label>
                                <textarea
                                    name="cover_letter"
                                    rows="2"
                                    placeholder="Add any experience note, passport readiness, or message..."
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                >{{ old('cover_letter') }}</textarea>
                            </div>

                            {{-- Executive Dark Bengali Advisory Box --}}
                            <div class="rounded-2xl bg-gradient-to-br from-slate-900 via-slate-900 to-blue-950 text-white p-4 sm:p-5 border border-slate-800 shadow-md space-y-1.5">
                                <div class="font-bold flex items-center gap-2 text-amber-400 text-xs sm:text-sm">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>বিঃদ্রঃ গুরুত্বপূর্ণ নির্দেশনা</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    সব পিডিএফ বা ডকুমেন্টস পরিষ্কারভাবে আপলোড করতে হবে। আবেদন পূরণ করার পর সাবমিট দেওয়ার পূর্বে আবেদন ভালোভাবে যাচাই করুন। ভুল বা অসত্য আবেদন বাতিল বলিয়া গণ্য হইবে।
                                </p>
                            </div>

                        </div>

                        {{-- Sticky Modal Action Footer --}}
                        <div class="sticky bottom-0 z-20 bg-white border-t border-slate-200 px-5 py-3.5 sm:px-6 sm:py-4 flex items-center justify-between gap-3 shadow-lg">
                            <button
                                type="button"
                                @click="applyModalOpen = false"
                                class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer"
                            >
                                Cancel
                            </button>

                            @if($isProfileComplete)
                                <button
                                    type="submit"
                                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs sm:text-sm font-bold transition-all shadow-md shadow-blue-600/20 hover:shadow-lg flex items-center gap-2 cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Submit Application
                                </button>
                            @else
                                <a
                                    href="{{ route('profile.edit') }}"
                                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-bold transition-all shadow-md shadow-blue-600/20 hover:shadow-lg flex items-center gap-1.5"
                                >
                                    <span>Complete Profile &rarr;</span>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth
</div>
@endsection
