@extends('layouts.site')

@section('title', 'Overseas Job Circulars | ' . ($siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.'))

@section('content')
<!-- Header Banner -->
<div class="bg-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-2 block">Government Verified Openings</span>
        <h1 class="text-3xl lg:text-4xl font-extrabold">Latest Overseas Job Circulars</h1>
        <p class="text-slate-400 mt-2 max-w-2xl text-base">Browse verified job openings with official Ministry clearance and visa demand orders.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($circulars as $circular)
            <div class="bg-white rounded-2xl border border-slate-200 flex flex-col justify-between hover:shadow-xl transition-all border-t-4 {{ $circular->status === 'open' ? 'border-t-blue-600' : 'border-t-slate-400' }} overflow-hidden">
                {{-- Featured Image --}}
                @if($circular->hasMedia('circular-image'))
                    <a href="{{ route('circulars.show', $circular->slug) }}" class="block">
                        <img
                            src="{{ $circular->getFirstMediaUrl('circular-image') }}"
                            alt="{{ $circular->title }}"
                            class="w-full h-48 object-cover"
                            loading="lazy"
                        >
                    </a>
                @endif

                <div class="p-6 flex flex-col flex-1">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="px-3 py-1 rounded-md text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $circular->country }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $circular->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($circular->status) }}
                            </span>
                        </div>

                        <h2 class="text-xl font-extrabold text-slate-900 mb-2 hover:text-blue-600 transition-colors">
                            <a href="{{ route('circulars.show', $circular->slug) }}">{{ $circular->title }}</a>
                        </h2>

                        <div class="text-xs font-semibold text-slate-500 mb-4 flex items-center gap-2">
                            <span>Category: <strong class="text-slate-800">{{ $circular->category }}</strong></span>
                            <span>•</span>
                            <span>Vacancies: <strong class="text-slate-800">{{ $circular->vacancy }}</strong></span>
                        </div>

                        <p class="text-slate-600 text-xs leading-relaxed mb-6 line-clamp-3">
                            {{ $circular->description }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-3 mt-auto">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Monthly Salary:</span>
                            <span class="font-bold text-slate-900 text-sm">{{ $circular->salary_range }}</span>
                        </div>

                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>Application Deadline:</span>
                            <span class="font-semibold text-rose-600">{{ $circular->deadline ? $circular->deadline->format('M d, Y') : 'Open Until Filled' }}</span>
                        </div>

                        {{-- Attachment count indicator --}}
                        @if($circular->getMedia('circular-attachments')->count())
                            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                {{ $circular->getMedia('circular-attachments')->count() }} attachment(s)
                            </div>
                        @endif

                        <a href="{{ route('circulars.show', $circular->slug) }}" class="block w-full py-3 text-center text-xs font-bold rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm">
                            View Requirements & Apply
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
                No job circulars available at this moment.
            </div>
        @endforelse
    </div>
</div>
@endsection
