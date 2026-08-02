@extends('layouts.site')

@section('title', 'Official Notice Board | ' . ($siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.'))

@section('content')
<!-- Header Banner -->
<div class="bg-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-2 block">Agency Announcements</span>
        <h1 class="text-3xl lg:text-4xl font-extrabold">Official Notice Board</h1>
        <p class="text-slate-400 mt-2 max-w-2xl text-base">Direct announcements regarding employer walk-in interviews, flight departures, medical checkups, and BMET clearances.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="space-y-6">
        @forelse($notices as $notice)
            <div class="bg-white rounded-2xl border p-6 lg:p-8 shadow-sm transition-all hover:shadow-md {{ $notice->is_pinned ? 'border-l-8 border-l-blue-600 border-slate-200 bg-blue-50/10' : 'border-slate-200' }}">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        @if($notice->is_pinned)
                            <span class="px-2.5 py-1 rounded-md text-xs font-extrabold bg-blue-600 text-white uppercase tracking-wider">
                                Pinned Notice
                            </span>
                        @endif
                        <span class="px-3 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $notice->category }}
                        </span>
                    </div>

                    <div class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Published: {{ $notice->published_at ? $notice->published_at->format('F d, Y') : 'Recent' }}
                    </div>
                </div>

                <h2 class="text-xl lg:text-2xl font-extrabold text-slate-900 mb-3">{{ $notice->title }}</h2>

                {{-- Featured Image --}}
                @if($notice->hasMedia('notice-image'))
                    <div class="mb-4 rounded-xl overflow-hidden border border-slate-200">
                        <img
                            src="{{ $notice->getFirstMediaUrl('notice-image') }}"
                            alt="{{ $notice->title }}"
                            class="w-full h-auto max-h-[500px] object-contain bg-slate-50"
                            loading="lazy"
                        >
                    </div>
                @endif

                <p class="text-slate-600 leading-relaxed text-sm whitespace-pre-line">{{ $notice->description }}</p>

                {{-- Attachments (PDFs / additional images) --}}
                @if($notice->getMedia('notice-attachments')->count())
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <h3 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Attachments
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($notice->getMedia('notice-attachments') as $attachment)
                                @if(str_starts_with($attachment->mime_type, 'image/'))
                                    {{-- Inline thumbnail for image attachments --}}
                                    <a href="{{ $attachment->getUrl() }}" target="_blank" class="group block w-32 rounded-lg overflow-hidden border border-slate-200 hover:border-blue-400 transition-colors">
                                        <img src="{{ $attachment->getUrl() }}" alt="{{ $attachment->file_name }}" class="w-full h-24 object-cover" loading="lazy">
                                        <span class="block text-[10px] text-center text-slate-500 p-1 truncate group-hover:text-blue-600">{{ $attachment->file_name }}</span>
                                    </a>
                                @else
                                    {{-- Download link for PDFs / docs --}}
                                    <a href="{{ $attachment->getUrl() }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-colors">
                                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                                        {{ $attachment->file_name }}
                                        <span class="text-[10px] text-slate-400 uppercase ml-1">({{ strtoupper(pathinfo($attachment->file_name, PATHINFO_EXTENSION)) }})</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
                No official notices published at the moment.
            </div>
        @endforelse
    </div>
</div>
@endsection
