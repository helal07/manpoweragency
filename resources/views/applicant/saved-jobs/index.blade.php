@extends('layouts.applicant')

@section('title', 'Saved Jobs')
@section('header_title', 'Saved Jobs')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Your Bookmarked Positions</h2>
    <p class="text-slate-500 text-sm mt-1">Jobs you have saved to review or apply to later.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($savedJobs ?? [] as $saved)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                        @if($saved->jobCircular->getFirstMediaUrl('circular-image'))
                            <img src="{{ $saved->jobCircular->getFirstMediaUrl('circular-image') }}" class="w-full h-full object-contain" alt="Logo">
                        @else
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('saved-jobs.destroy', $saved->id) ?? '#' }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors" title="Remove Bookmark">
                            <svg class="w-6 h-6 fill-current text-blue-600" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </button>
                    </form>
                </div>
                
                <h3 class="text-lg font-bold text-slate-800 line-clamp-2 mb-1">
                    <a href="{{ route('circulars.show', $saved->jobCircular->slug) }}" class="hover:text-blue-600 transition-colors">{{ $saved->jobCircular->title }}</a>
                </h3>
                <div class="text-sm text-slate-500 font-medium mb-4">{{ $saved->jobCircular->employer_name ?? 'Confidential Employer' }}</div>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-slate-50 text-slate-600 text-xs font-semibold border border-slate-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $saved->jobCircular->location ?? 'Various' }}
                    </span>
                    @if($saved->jobCircular->salary_range)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $saved->jobCircular->salary_range }}
                    </span>
                    @endif
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl mt-auto">
                <a href="{{ route('circulars.show', $saved->jobCircular->slug) }}" class="block w-full py-2.5 text-center bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 transition-colors shadow-sm">
                    View & Apply
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">No saved jobs</h3>
                <p class="text-slate-500 mb-4">You haven't bookmarked any jobs yet. Browse our job circulars to find opportunities that match your skills.</p>
                <a href="{{ route('circulars.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">
                    Browse Jobs
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection
