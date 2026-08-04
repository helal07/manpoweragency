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

            <div class="bg-slate-900 text-white rounded-2xl p-6 border border-slate-800 text-xs space-y-3">
                <div class="font-bold text-blue-400 uppercase tracking-wider">Government License Notice</div>
                <p class="text-slate-300 leading-relaxed">This job circular is issued under BMET Govt. License {{ $siteSettings['bmet_license_no'] ?? 'RL-1452' }}. All selected candidates receive official employment contracts and BMET clearance prior to flight departure.</p>
            </div>
        </div>
    </div>

    {{-- Application Modal --}}
    @auth('web')
        <div
            x-show="applyModalOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 lg:p-8"
            style="display: none;"
            @keydown.escape.window="applyModalOpen = false"
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
                class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"
                @click="applyModalOpen = false"
            ></div>

            {{-- Modal Content --}}
            <div
                x-show="applyModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto z-10 border border-slate-200 p-6 sm:p-8 space-y-6"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                    <div>
                        <div class="text-xs font-bold text-blue-600 uppercase tracking-wider">Job Application</div>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-1">{{ $circular->title }}</h2>
                        <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                            <span>Destination: <strong class="text-slate-700">{{ $circular->country }}</strong></span>
                            <span>•</span>
                            <span>Salary: <strong class="text-slate-700">{{ $circular->salary_range }}</strong></span>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="applyModalOpen = false"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Applicant Snapshot Box --}}
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 text-xs text-slate-600 flex items-center justify-between gap-4">
                    <div>
                        <div class="font-bold text-slate-800 text-sm">Applying as: {{ auth()->user()->name }}</div>
                        <div class="text-slate-500 mt-0.5">{{ auth()->user()->email }} · {{ auth()->user()->mobile_no ?? 'No mobile set' }}</div>
                    </div>
                    <a href="{{ route('profile.edit') }}" target="_blank" class="text-blue-600 font-semibold hover:underline shrink-0 text-xs">
                        Edit Profile &rarr;
                    </a>
                </div>

                {{-- Application Form --}}
                <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="job_circular_id" value="{{ $circular->id }}">

                    @if($circular->customFields->count() > 0)
                        <div class="space-y-4">
                            <div class="border-b border-slate-100 pb-2">
                                <h3 class="text-sm font-bold text-slate-900">Position Requirements & Documents</h3>
                                <p class="text-xs text-slate-500">Please provide the specific details requested for this circular.</p>
                            </div>

                            @foreach($circular->customFields as $field)
                                @php
                                    $isRequired = (bool) ($field->pivot->is_required ?? false);
                                    $fieldKey = "custom_fields.{$field->id}";
                                    $oldVal = old("custom_fields.{$field->id}");
                                @endphp

                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700">
                                        {{ $field->label }}
                                        @if($isRequired)
                                            <span class="text-rose-500">*</span>
                                        @else
                                            <span class="text-slate-400 font-normal">(Optional)</span>
                                        @endif
                                    </label>

                                    @if($field->help_text)
                                        <p class="text-[11px] text-slate-500">{{ $field->help_text }}</p>
                                    @endif

                                    {{-- Render by type --}}
                                    @if($field->type === 'text')
                                        <input
                                            type="text"
                                            name="custom_fields[{{ $field->id }}]"
                                            value="{{ $oldVal }}"
                                            placeholder="{{ $field->placeholder ?? 'Enter ' . $field->label }}"
                                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error($fieldKey) border-rose-500 @enderror"
                                            {{ $isRequired ? 'required' : '' }}
                                        >
                                    @elseif($field->type === 'textarea')
                                        <textarea
                                            name="custom_fields[{{ $field->id }}]"
                                            rows="3"
                                            placeholder="{{ $field->placeholder ?? 'Enter ' . $field->label }}"
                                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error($fieldKey) border-rose-500 @enderror"
                                            {{ $isRequired ? 'required' : '' }}
                                        >{{ $oldVal }}</textarea>
                                    @elseif($field->type === 'number')
                                        <input
                                            type="number"
                                            name="custom_fields[{{ $field->id }}]"
                                            value="{{ $oldVal }}"
                                            placeholder="{{ $field->placeholder ?? '0' }}"
                                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error($fieldKey) border-rose-500 @enderror"
                                            {{ $isRequired ? 'required' : '' }}
                                        >
                                    @elseif($field->type === 'date')
                                        <input
                                            type="date"
                                            name="custom_fields[{{ $field->id }}]"
                                            value="{{ $oldVal }}"
                                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error($fieldKey) border-rose-500 @enderror"
                                            {{ $isRequired ? 'required' : '' }}
                                        >
                                    @elseif($field->type === 'select')
                                        <select
                                            name="custom_fields[{{ $field->id }}]"
                                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error($fieldKey) border-rose-500 @enderror"
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
                                            <span class="text-xs font-semibold text-slate-700">Yes / Confirm</span>
                                        </label>
                                    @elseif($field->type === 'file')
                                        <div class="mt-1">
                                            <input
                                                type="file"
                                                name="custom_fields[{{ $field->id }}]"
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp"
                                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer border border-slate-200 rounded-xl p-1 @error($fieldKey) border-rose-500 @enderror"
                                                {{ $isRequired ? 'required' : '' }}
                                            >
                                            <p class="text-[10px] text-slate-400 mt-1">Accepted: PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                                        </div>
                                    @endif

                                    @error($fieldKey)
                                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Cover Letter / Message --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">
                            Cover Letter / Remarks <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <textarea
                            name="cover_letter"
                            rows="3"
                            placeholder="Write any note, past experience, or message to the recruitment team..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >{{ old('cover_letter') }}</textarea>
                    </div>

                    {{-- Submit & Cancel Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button
                            type="button"
                            @click="applyModalOpen = false"
                            class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors shadow-md flex items-center gap-2 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth
</div>
@endsection
