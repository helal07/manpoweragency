@extends('layouts.site')

@section('title', ($siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.') . ' | Leading Overseas Recruitment Agency')

@section('content')
    @php
        $activeBanner = isset($heroBanners) && $heroBanners->count() > 0 ? $heroBanners->first() : null;
    @endphp
    <!-- Hero Section -->
    <div class="relative bg-slate-950 text-white py-20 lg:py-28 overflow-hidden border-b border-slate-800">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-900/30 via-slate-950 to-slate-950 z-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7">
                    @if($siteSettings['show_bmet_license'] ?? true)
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-6">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        Govt. Approved License: {{ $siteSettings['bmet_license_no'] ?? 'RL-1452' }}
                    </span>
                    @endif
                    <h1 class="text-4xl sm:text-5xl lg:text-4xl font-extrabold tracking-tight leading-tight text-white mb-6">
                        {{ $activeBanner ? $activeBanner->title : ($siteSettings['site_name'] ?? 'Empowering Global Opportunities with Trusted Manpower') }}
                    </h1>
                    <p class="text-lg text-slate-300 mb-8 leading-relaxed">
                        {{ $activeBanner && $activeBanner->subtitle ? $activeBanner->subtitle : ($siteSettings['site_tagline'] ?? 'Connecting skilled Bangladeshi workforce with reputable employers across Saudi Arabia, UAE, Qatar, Malaysia & Europe.') }}
                    </p>
                    <div class="flex flex-wrap gap-4 mb-10">
                        <a href="{{ $activeBanner && $activeBanner->cta_url ? url($activeBanner->cta_url) : url('/job-circulars') }}" class="px-7 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2 group">
                            <span>{{ $activeBanner && $activeBanner->cta_label ? $activeBanner->cta_label : 'Browse Job Circulars' }}</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ url('/about') }}" class="px-7 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 font-semibold border border-slate-700 transition-all">
                            Learn About Agency
                        </a>
                    </div>
                </div>

                <!-- Statistics Floating Grid -->
                <div class="lg:col-span-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl backdrop-blur-sm">
                            <div class="text-3xl lg:text-4xl font-extrabold text-blue-400 mb-1">{{ $siteSettings['stat_deployed'] ?? '15,400+' }}</div>
                            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">Workers Deployed</div>
                        </div>
                        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl backdrop-blur-sm">
                            <div class="text-3xl lg:text-4xl font-extrabold text-indigo-400 mb-1">{{ $siteSettings['stat_countries'] ?? '12+' }}</div>
                            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">Destination Countries</div>
                        </div>
                        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl backdrop-blur-sm">
                            <div class="text-3xl lg:text-4xl font-extrabold text-teal-400 mb-1">{{ $siteSettings['stat_clients'] ?? '85+' }}</div>
                            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">Global Employers</div>
                        </div>
                        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl backdrop-blur-sm">
                            <div class="text-3xl lg:text-4xl font-extrabold text-emerald-400 mb-1">{{ $siteSettings['stat_success'] ?? '98.5%' }}</div>
                            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">Visa Success Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Official Notice Banner / Highlights -->
    @if(isset($latestNotices) && $latestNotices->count() > 0)
    <div class="bg-blue-950 border-b border-blue-900 text-blue-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase bg-blue-600 text-white animate-pulse">Official Notice</span>
                <span class="text-sm font-semibold truncate max-w-xl">{{ $latestNotices->first()->title }}</span>
            </div>
            <a href="{{ url('/notices') }}" class="text-xs font-bold text-blue-300 hover:text-white flex items-center gap-1 underline underline-offset-4">
                View All Announcements &rarr;
            </a>
        </div>
    </div>
    @endif

    <!-- About Agency Teaser -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 lg:p-12 grid lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-8">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-2 block">Welcome To Our Agency</span>
                <h2 class="text-2xl lg:text-3xl font-extrabold text-slate-900 mb-4">Transparent & Ethical Overseas Employment Services</h2>
                <p class="text-slate-600 leading-relaxed text-base">
                    {{ $siteSettings['about_teaser'] ?? 'Global Manpower Overseas Ltd. is a premier government-licensed recruitment agency in Bangladesh. We provide comprehensive recruitment solutions for international corporate clients while safeguarding worker rights and compliance.' }}
                </p>
            </div>
            <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-xs font-bold text-slate-500 uppercase">Emergency Hotline</div>
                    <div class="text-lg font-extrabold text-slate-900">{{ $siteSettings['company_hotline'] ?? '+880 1711-009988' }}</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-xs font-bold text-slate-500 uppercase">Govt. BMET License</div>
                    <div class="text-sm font-bold text-blue-600">{{ $siteSettings['bmet_license_no'] ?? 'RL-1452' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Job Circulars Section -->
    <div class="bg-slate-100 py-16 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-1 block">Overseas Opportunities</span>
                    <h2 class="text-3xl font-extrabold text-slate-900">Featured Job Circulars</h2>
                </div>
                <a href="{{ url('/job-circulars') }}" class="mt-4 sm:mt-0 text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    View All Circulars &rarr;
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($featuredCirculars as $circular)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-lg transition-all">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                    {{ $circular->country }}
                                </span>
                                <span class="text-xs text-slate-500 font-medium">
                                    {{ $circular->vacancy }} Vacancies
                                </span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-lg mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                                <a href="{{ route('circulars.show', $circular->slug) }}">{{ $circular->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $circular->description }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs text-slate-600 mb-3">
                                <span>Salary:</span>
                                <span class="font-bold text-slate-900">{{ $circular->salary_range }}</span>
                            </div>
                            <a href="{{ route('circulars.show', $circular->slug) }}" class="block w-full py-2.5 text-center text-xs font-bold rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                View Job Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-xl p-8 text-center text-slate-500 border border-slate-200">
                        No active circulars at the moment.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Core Recruitment Services -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-1 block">What We Offer</span>
                <h2 class="text-3xl font-extrabold text-slate-900">Comprehensive Recruitment & Mobility Services</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-blue-300 hover:bg-blue-50/30 transition-all group">
                        <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $service->title }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $service->short_description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Organizer / Leadership Section -->
    <div class="bg-slate-900 text-white py-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-1 block">Leadership Team</span>
                <h2 class="text-3xl font-extrabold text-white">Company Board & Organizers</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($leaders as $leader)
                    <div class="bg-slate-950 rounded-2xl p-6 border border-slate-800 flex flex-col justify-between hover:border-slate-700 transition-all">
                        <div>
                            @if($leader->photo)
                                <img src="{{ asset('storage/' . $leader->photo) }}" alt="{{ $leader->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-blue-500 mb-4 shadow-lg">
                            @else
                                <div class="w-16 h-16 rounded-full bg-blue-600 text-white font-extrabold text-2xl flex items-center justify-center mb-4">
                                    {{ strtoupper(substr($leader->name, 0, 1)) }}
                                </div>
                            @endif
                            <h3 class="text-xl font-bold text-white mb-1">{{ $leader->name }}</h3>
                            <span class="text-xs font-bold text-blue-400 uppercase tracking-wider block mb-3">{{ $leader->designation }}</span>
                            <p class="text-slate-400 text-sm leading-relaxed">{{ $leader->bio }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- International Employer Clients (Single-Row Circular Carousel) -->
    <div class="py-16 bg-slate-50 border-t border-slate-200 overflow-hidden relative">
        <style>
            @keyframes clientMarquee {
                0% {
                    transform: translateX(0%);
                }
                100% {
                    transform: translateX(-50%);
                }
            }
            .client-carousel-track {
                display: flex;
                width: max-content;
                animation: clientMarquee 38s linear infinite;
            }
            .client-carousel-track:hover {
                animation-play-state: paused;
            }
        </style>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-1 block">Global Partnerships</span>
                    <h2 class="text-2xl lg:text-3xl font-extrabold text-slate-900">Valued Employer Partners</h2>
                </div>
                <div>
                    <a href="{{ url('/clients') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors group">
                        <span>View All Client Partners</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Carousel Container with Edge Gradient Fades -->
        <div class="relative w-full overflow-hidden">
            <!-- Left & Right Fade Gradients -->
            <div class="pointer-events-none absolute inset-y-0 left-0 w-16 sm:w-28 bg-gradient-to-r from-slate-50 to-transparent z-10"></div>
            <div class="pointer-events-none absolute inset-y-0 right-0 w-16 sm:w-28 bg-gradient-to-l from-slate-50 to-transparent z-10"></div>

            <!-- Single Row Infinite Moving Track -->
            <div class="client-carousel-track gap-5 py-2 px-4">
                {{-- Loop twice for continuous seamless infinite loop --}}
                @for ($loopCount = 0; $loopCount < 2; $loopCount++)
                    @foreach($clients as $client)
                        <div class="w-[280px] sm:w-[320px] flex-shrink-0 bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                            <div>
                                <!-- Top: Logo or Brand Avatar -->
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <div class="h-12 w-28 flex items-center justify-start overflow-hidden">
                                        @if($client->logo)
                                            <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="max-h-12 max-w-full object-contain filter group-hover:scale-105 transition-transform">
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-extrabold text-sm flex items-center justify-center shadow-sm">
                                                {{ $client->initials }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $client->country }}
                                    </span>
                                </div>

                                <!-- Client Name -->
                                <h3 class="text-sm font-extrabold text-slate-900 line-clamp-1 group-hover:text-blue-600 transition-colors mb-1" title="{{ $client->name }}">
                                    {{ $client->name }}
                                </h3>

                                <!-- Sector -->
                                <p class="text-xs text-slate-500 line-clamp-1 mb-2">
                                    {{ $client->sector }}
                                </p>
                            </div>

                            <!-- Bottom Website Link if exists -->
                            @if($client->website_url)
                                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-blue-600 font-semibold">
                                    <span>Official Partner</span>
                                    <a href="{{ $client->website_url }}" target="_blank" rel="noopener noreferrer" class="hover:underline flex items-center gap-1">
                                        <span>Website</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            @else
                                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                                    <span>Verified Employer</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>
    </div>
@endsection
