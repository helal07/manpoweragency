@extends('layouts.site')

@section('title', 'Recruitment & Mobility Services | ' . ($siteSettings['site_name'] ?? 'Global Manpower Overseas Ltd.'))

@section('content')
<!-- Header Banner -->
<div class="bg-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-2 block">Our Solutions</span>
        <h1 class="text-3xl lg:text-4xl font-extrabold">Overseas Recruitment & Mobility Services</h1>
        <p class="text-slate-400 mt-2 max-w-2xl text-base">End-to-end solutions for foreign employer companies and Bangladeshi job seekers.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        @foreach($services as $service)
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-md hover:border-blue-300 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold mb-6 text-xl shadow-md shadow-blue-600/20">
                        {{ $loop->iteration }}
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-3">{{ $service->title }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">{{ $service->short_description }}</p>
                    @if($service->full_description)
                        <p class="text-xs text-slate-500 leading-relaxed pt-3 border-t border-slate-100">{{ $service->full_description }}</p>
                    @endif
                </div>
                <div class="pt-6">
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-blue-600">
                        Government Approved Process &check;
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recruitment Process Workflow Timeline -->
    <div class="bg-slate-900 text-white rounded-3xl p-8 lg:p-12 border border-slate-800">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-1 block">Step-By-Step Workflow</span>
            <h2 class="text-2xl font-extrabold text-white">How We Deploy Manpower</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-4 bg-slate-950 rounded-xl border border-slate-800">
                <div class="text-blue-400 font-extrabold text-lg mb-1">01. Demand Receipt</div>
                <p class="text-xs text-slate-400">Employer posts visa demand order with embassy endorsement.</p>
            </div>
            <div class="p-4 bg-slate-950 rounded-xl border border-slate-800">
                <div class="text-blue-400 font-extrabold text-lg mb-1">02. Screening & Test</div>
                <p class="text-xs text-slate-400">Shortlisting and trade testing at certified technical workshops.</p>
            </div>
            <div class="p-4 bg-slate-950 rounded-xl border border-slate-800">
                <div class="text-blue-400 font-extrabold text-lg mb-1">03. Medical & Visa</div>
                <p class="text-xs text-slate-400">GAMCA medical checkup, MOFA visa stamping & BMET Smart Card.</p>
            </div>
            <div class="p-4 bg-slate-950 rounded-xl border border-slate-800">
                <div class="text-blue-400 font-extrabold text-lg mb-1">04. Flight Departure</div>
                <p class="text-xs text-slate-400">Pre-departure briefing, airline ticket issue & airport assistance.</p>
            </div>
        </div>
    </div>
</div>
@endsection
