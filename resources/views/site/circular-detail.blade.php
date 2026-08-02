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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
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

            {{-- Attachments (PDFs / additional images) --}}
            @if($circular->getMedia('circular-attachments')->count())
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        Attachments & Documents
                    </h2>
                    <div class="space-y-4">
                        @foreach($circular->getMedia('circular-attachments') as $attachment)
                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                {{-- Inline display for image attachments --}}
                                <div class="rounded-xl overflow-hidden border border-slate-200">
                                    <a href="{{ $attachment->getUrl() }}" target="_blank">
                                        <img src="{{ $attachment->getUrl() }}" alt="{{ $attachment->file_name }}" class="w-full h-auto max-h-[500px] object-contain bg-slate-50" loading="lazy">
                                    </a>
                                    <div class="px-4 py-2 bg-slate-50 border-t border-slate-200 text-xs text-slate-500">
                                        {{ $attachment->file_name }} — <a href="{{ $attachment->getUrl() }}" target="_blank" class="text-blue-600 hover:underline">Open Full Size</a>
                                    </div>
                                </div>
                            @else
                                {{-- Download link for PDFs / docs --}}
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
                        @else
                            <form action="{{ route('applications.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="job_circular_id" value="{{ $circular->id }}">
                                <button type="submit" class="w-full py-3.5 text-center font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-md">
                                    Apply Now
                                </button>
                            </form>
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
</div>
@endsection
