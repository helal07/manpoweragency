@extends('layouts.site')

@section('title', 'Our Global Clients | ' . ($siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.'))

@section('content')
<!-- Header Banner -->
<div class="bg-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-2 block">International Employers</span>
        <h1 class="text-3xl lg:text-4xl font-extrabold">Our Valued Global Clients</h1>
        <p class="text-slate-400 mt-2 max-w-2xl text-base">Partnering with premier companies across Saudi Arabia, UAE, Qatar, Kuwait, Malaysia, and Eastern Europe.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($clients as $client)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-lg transition-all group">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <span class="px-3 py-1 rounded-md text-xs font-extrabold bg-blue-50 text-blue-600 border border-blue-200">
                            {{ $client->country }}
                        </span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500" title="Active Client Partner"></span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                        {{ $client->name }}
                    </h3>
                    <p class="text-xs font-medium text-slate-500 mb-4">{{ $client->sector }}</p>
                </div>

                @if($client->website_url)
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ $client->website_url }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <span>Visit Official Website</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
