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
    <!-- Section Title Styled like Reference Image 2 -->
    <div class="text-center mb-12">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-700 tracking-wider uppercase inline-block relative pb-3">
            Our Clients
            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-16 h-1 bg-emerald-500 rounded-full"></span>
        </h2>
        <p class="text-sm text-slate-500 mt-3 max-w-xl mx-auto">Valued government bodies, corporations, and international employers trusting our workforce solutions.</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($clients as $client)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 flex flex-col justify-between hover:shadow-xl hover:border-emerald-300 transition-all duration-300 group">
                <div>
                    <!-- Header with Country badge & Website link -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                            {{ $client->country }}
                        </span>
                        @if($client->website_url)
                            <a href="{{ $client->website_url }}" target="_blank" rel="noopener noreferrer" class="text-[11px] font-semibold text-slate-400 hover:text-emerald-700 transition-colors flex items-center gap-1" title="Visit Website">
                                <span>Website</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Verified
                            </span>
                        @endif
                    </div>

                    <!-- Dedicated Logo Container with Auto-fit Aspect Ratio -->
                    <div class="w-full h-32 flex items-center justify-center p-3 mb-4 bg-slate-50/80 rounded-xl border border-slate-100 group-hover:bg-emerald-50/20 group-hover:border-emerald-100 transition-all duration-300">
                        @if($client->logo)
                            <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="transition-transform duration-300 group-hover:scale-105" style="max-height: 85px; max-width: 100%; width: auto; height: auto; object-fit: contain; margin: 0 auto; display: block;" loading="lazy">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white font-extrabold text-lg flex items-center justify-center shadow-sm">
                                {{ $client->initials }}
                            </div>
                        @endif
                    </div>

                    <!-- Client Name (Centered Bold Emerald like Image 2) -->
                    <h3 class="text-base font-bold text-emerald-800 group-hover:text-emerald-950 transition-colors text-center line-clamp-2 min-h-[2.8rem] flex items-center justify-center leading-snug px-1" title="{{ $client->name }}">
                        {{ $client->name }}
                    </h3>

                    <!-- Sector -->
                    <div class="flex items-center justify-center gap-1.5 text-xs font-medium text-slate-500 mt-1 mb-4 text-center">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="line-clamp-1">{{ $client->sector }}</span>
                    </div>
                </div>

                <!-- Footer Action -->
                @if($client->website_url)
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <a href="{{ $client->website_url }}" target="_blank" rel="noopener noreferrer" class="font-bold text-emerald-700 hover:text-emerald-900 flex items-center gap-1.5 group/link">
                            <span>Visit Website</span>
                            <svg class="w-3.5 h-3.5 transform group-link-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <span class="w-2 h-2 rounded-full bg-emerald-500" title="Active Client Partner"></span>
                    </div>
                @else
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                        <span>Verified Corporate Client</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500" title="Active Client Partner"></span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
