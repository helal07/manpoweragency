<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.' }} | Portal</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-['Plus_Jakarta_Sans',sans-serif] bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen relative selection:bg-[#F59E0B] selection:text-[#0F172A]">
        
        <!-- Clean Off-White Background with Soft Vignette -->
        <div class="fixed inset-0 bg-cover bg-center bg-no-repeat -z-20 pointer-events-none" style="background-image: url('{{ asset('images/airplane_bg.png') }}');"></div>
        <div class="fixed inset-0 bg-slate-950/30 backdrop-blur-[2px] -z-10 pointer-events-none"></div>

        <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- 60-30-10 Styled Container Card -->
            <div class="w-full max-w-4xl bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl shadow-2xl overflow-hidden grid lg:grid-cols-12 my-auto">
                
                <!-- Left Banner: 30% Secondary (Deep Navy Blue #0F172A & Slate Blue #1E3A8A) -->
                <div class="lg:col-span-5 relative p-8 flex flex-col justify-between overflow-hidden min-h-[280px] lg:min-h-full border-b lg:border-b-0 lg:border-r border-[#1E3A8A]/30 bg-[#0F172A] text-white">
                    <div class="absolute inset-0 bg-cover bg-center -z-10 opacity-25 scale-105" style="background-image: url('{{ asset('images/agency_employees.png') }}');"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0F172A] via-[#0F172A]/80 to-[#1E3A8A]/40 -z-10"></div>

                    <!-- Header Brand -->
                    <div>
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group mb-4">
                            @if(!empty($siteSettings['logo_url']))
                                <img src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}" class="h-10 w-auto object-contain">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-[#F59E0B] flex items-center justify-center font-bold text-xl text-[#0F172A] shadow-md group-hover:bg-[#D97706] transition-colors">
                                    {{ strtoupper(substr($siteSettings['site_name'] ?? 'M', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="text-lg font-extrabold tracking-tight text-white block leading-tight">
                                    GLOBAL MANPOWER <span class="text-[#F59E0B]">OVERSEAS</span>
                                </span>
                            </div>
                        </a>

                        <!-- 10% Accent Badge: Warm Amber Gold #F59E0B -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#0F172A] text-[#F59E0B] border border-[#F59E0B]/50 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-[#F59E0B] animate-pulse"></span>
                            Govt. License: {{ $siteSettings['bmet_license_no'] ?? 'RL-1452' }}
                        </span>
                    </div>

                    <!-- Overseas Agency Overview -->
                    <div class="mt-8">
                        <h3 class="text-xl font-extrabold text-white mb-2 leading-tight">
                            International Overseas Placement Portal
                        </h3>
                        <p class="text-xs text-slate-300 leading-relaxed mb-6">
                            Government approved manpower recruitment agency connecting Bangladesh talent with global opportunities across Saudi Arabia, UAE, Qatar, Kuwait, & Europe.
                        </p>
                        
                        <div class="space-y-2 text-xs font-semibold text-slate-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#10B981] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>BMET Verified Overseas Vacancies</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#10B981] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Real-time Visa & Medical Status Tracker</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Form Slot: 60% Primary Pure White #FFFFFF Background -->
                <div class="lg:col-span-7 p-8 sm:p-10 flex flex-col justify-center bg-[#FFFFFF]">
                    {{ $slot }}
                </div>
            </div>

            <!-- Back to Home Link -->
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs text-white hover:text-[#F59E0B] transition-colors font-semibold drop-shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Public Homepage
                </a>
            </div>
        </div>
    </body>
</html>
