@extends('layouts.site')

@section('title', 'Our Global Clients | ' . ($siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.'))

@section('content')
<!-- Header Banner -->
<div class="bg-slate-950 text-white py-16 border-b border-slate-800 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/20 to-transparent pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <span class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-2 block">International Employers</span>
        <h1 class="text-3xl lg:text-4xl font-extrabold">Our Valued Global Clients</h1>
        <p class="text-slate-400 mt-2 max-w-2xl text-base">Partnering with premier corporate organizations across Saudi Arabia, UAE, Qatar, Kuwait, Malaysia, and Eastern Europe to supply qualified human resources.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($clients as $client)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-xl hover:border-blue-300 transition-all duration-300 group">
                <div>
                    <!-- Header with Logo / Initials & Country badge -->
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <div class="h-14 w-28 flex items-center justify-start overflow-hidden">
                            @if($client->logo)
                                <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="max-h-14 max-w-full object-contain filter group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-extrabold text-base flex items-center justify-center shadow-sm">
                                    {{ $client->initials }}
                                </div>
                            @endif
                        </div>
                        <span class="px-3 py-1 rounded-md text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $client->country }}
                        </span>
                    </div>

                    <!-- Client Name -->
                    <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                        {{ $client->name }}
                    </h3>

                    <!-- Sector -->
                    <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 mb-4">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>{{ $client->sector }}</span>
                    </div>
                </div>

                <!-- Footer Action -->
                @if($client->website_url)
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ $client->website_url }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1.5 group/link">
                            <span>Visit Official Website</span>
                            <svg class="w-3.5 h-3.5 transform group-link-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <span class="w-2 h-2 rounded-full bg-emerald-500" title="Active Client Partner"></span>
                    </div>
                @else
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                        <span>Verified Corporate Client</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500" title="Active Client Partner"></span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
