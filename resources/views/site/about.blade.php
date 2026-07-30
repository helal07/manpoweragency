@extends('layouts.site')

@section('title', 'About Us | ' . ($siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.'))

@section('content')
<!-- Header Banner -->
<div class="bg-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30 uppercase tracking-wider mb-4 inline-block">
            Govt. BMET License: {{ $siteSettings['bmet_license_no'] ?? 'RL-1452' }}
        </span>
        <h1 class="text-3xl lg:text-4xl font-extrabold">About {{ $siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.' }}</h1>
        <p class="text-slate-400 mt-2 max-w-2xl text-base">Over 18 years of pioneering excellence in overseas human resource recruitment and ethical labor migration.</p>
    </div>
</div>

<!-- Company Story & Overview -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid lg:grid-cols-12 gap-12 items-start">
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Who We Are</h2>
                <p class="text-slate-600 leading-relaxed mb-4">
                    {{ $siteSettings['about_teaser'] ?? 'Global Manpower Overseas Ltd. is a premier government-licensed overseas recruitment agency in Bangladesh (RL-1452). Specializing in sourcing, trade-testing, and deploying skilled, semi-skilled, and professional personnel across the Middle East, Southeast Asia, and Europe.' }}
                </p>
                <p class="text-slate-600 leading-relaxed">
                    With our state-of-the-art Trade Testing Center and experienced management team, we bridge the gap between Bangladeshi talent and international corporate demand, upholding the highest standards of worker welfare, legal compliance, and operational efficiency.
                </p>
            </div>

            <!-- Mission & Vision Cards -->
            <div class="grid sm:grid-cols-2 gap-6">
                <div class="bg-blue-50/50 border border-blue-200 rounded-2xl p-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold mb-3">M</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Our Mission</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $siteSettings['mission_statement'] ?? 'To empower Bangladeshi workforce with dignified, legal overseas employment while providing top-tier labor solutions to global employers.' }}</p>
                </div>

                <div class="bg-indigo-50/50 border border-indigo-200 rounded-2xl p-6">
                    <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold mb-3">V</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Our Vision</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $siteSettings['vision_statement'] ?? 'To be the most ethical, transparent, and preferred overseas manpower consultancy in South Asia.' }}</p>
                </div>
            </div>
        </div>

        <!-- Key Info & Accreditations -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-slate-900 text-white rounded-2xl p-8 border border-slate-800 shadow-lg">
                <h3 class="text-xl font-bold mb-6 text-white border-b border-slate-800 pb-4">Government Accreditation</h3>
                <ul class="space-y-4 text-sm text-slate-300">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span><strong>BMET License:</strong> {{ $siteSettings['bmet_license_no'] ?? 'RL-1452' }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Ministry of Expatriates' Welfare & Overseas Employment Approved</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>BAIRA (Bangladesh Association of International Recruiting Agencies) Member</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Authorized Embassy Enrolment Agency (Saudi Arabia, UAE, Qatar, Malaysia)</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 text-center">
                <h4 class="font-bold text-slate-900 mb-2">Visit Our Corporate Office</h4>
                <p class="text-sm text-slate-600 mb-4">{{ $siteSettings['company_address'] ?? 'House 42, Road 11, Block D, Banani, Dhaka-1213, Bangladesh' }}</p>
                <div class="inline-block text-xs font-extrabold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-md border border-blue-200">
                    Office Hours: Sat - Thu (09:00 AM - 06:00 PM)
                </div>
            </div>
        </div>
    </div>

    <!-- Leadership Section -->
    <div class="mt-16 pt-16 border-t border-slate-200">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-1 block">Board of Directors</span>
            <h2 class="text-3xl font-extrabold text-slate-900">Our Leadership Team</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($leaders as $leader)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        @if($leader->photo)
                            <img src="{{ asset('storage/' . $leader->photo) }}" alt="{{ $leader->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-blue-600 mb-4 shadow-md">
                        @else
                            <div class="w-16 h-16 rounded-full bg-blue-600 text-white font-extrabold text-2xl flex items-center justify-center mb-4">
                                {{ strtoupper(substr($leader->name, 0, 1)) }}
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $leader->name }}</h3>
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-3">{{ $leader->designation }}</span>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $leader->bio }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
