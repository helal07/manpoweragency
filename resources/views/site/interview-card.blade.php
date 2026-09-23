<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Admit Card - {{ $applicant?->name ?? 'Candidate' }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"Space Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            navy: '#0F172A',
                            blue: '#1E3A8A',
                            gold: '#D97706',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                box-shadow: none !important;
                border: 2px solid #0f172a !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased min-h-screen py-8 px-4 sm:px-6">

    <div class="max-w-3xl mx-auto mb-4 no-print flex items-center justify-between">
        <a href="/" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Website
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-brand-blue hover:bg-blue-900 text-white text-xs font-bold rounded-xl shadow-md flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Admit Card</span>
            </button>
        </div>
    </div>

    <!-- Main Printable Card Container -->
    <div class="print-card max-w-3xl mx-auto bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        
        <!-- Header Banner -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-brand-navy via-slate-900 to-brand-blue text-white border-b-4 border-amber-500 relative">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if(!empty($siteSettings?->logo_path))
                        <img src="{{ asset('storage/' . $siteSettings->logo_path) }}" alt="Logo" class="h-14 w-auto bg-white p-1 rounded-xl shadow-md">
                    @else
                        <div class="w-12 h-12 bg-amber-500 text-brand-navy font-extrabold text-xl flex items-center justify-center rounded-xl shadow-md">
                            {{ substr($siteSettings?->site_name ?? 'G', 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">{{ $siteSettings?->site_name ?? 'Global Manpower Overseas Ltd.' }}</h1>
                        <p class="text-xs text-amber-300 font-medium tracking-wide mt-0.5">Govt. Approved Overseas Recruiting Agency | {{ $siteSettings?->bmet_license_no ?? 'RL-1452' }}</p>
                        <p class="text-[11px] text-slate-300 mt-0.5">{{ $siteSettings?->address ?? 'House 42, Road 11, Banani, Dhaka-1213, Bangladesh' }}</p>
                    </div>
                </div>

                <div class="sm:text-right border-t sm:border-t-0 border-white/10 pt-3 sm:pt-0">
                    <span class="inline-block bg-amber-500/20 border border-amber-400/40 text-amber-300 text-[10px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg">
                        Admit Card / প্রবেশপত্র
                    </span>
                    <p class="text-[11px] text-slate-300 font-mono mt-1">SL: GMO-APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        <!-- Document Sub-Header -->
        <div class="bg-slate-50 border-b border-slate-200 px-6 sm:px-8 py-3.5 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Interview & Trade Test Verification Slip</span>
            </div>
            <div class="text-xs text-slate-500 font-medium">
                Issue Date: <span class="font-bold text-slate-700">{{ $application->interview_called_at ? $application->interview_called_at->format('d M, Y') : now()->format('d M, Y') }}</span>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            
            <!-- Candidate & Job Summary Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/80 p-5 rounded-xl border border-slate-200/80">
                
                <!-- Candidate Details -->
                <div class="space-y-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-blue border-b border-slate-200 pb-1.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Candidate Information
                    </h3>
                    
                    <div class="grid grid-cols-3 gap-1 text-xs">
                        <span class="text-slate-500 font-medium">Name:</span>
                        <span class="col-span-2 font-bold text-slate-900">{{ $applicant?->name ?? 'N/A' }}</span>

                        <span class="text-slate-500 font-medium">Father's Name:</span>
                        <span class="col-span-2 font-semibold text-slate-800">{{ $applicant?->fathers_name ?: 'Not Provided' }}</span>

                        <span class="text-slate-500 font-medium">Mobile:</span>
                        <span class="col-span-2 font-mono font-bold text-slate-900">{{ $applicant?->phone ?: $applicant?->mobile_no }}</span>

                        <span class="text-slate-500 font-medium">NID / Passport:</span>
                        <span class="col-span-2 font-mono font-semibold text-slate-800">{{ $applicant?->nid_passport ?: 'Not Provided' }}</span>
                    </div>
                </div>

                <!-- Applied Circular Info -->
                <div class="space-y-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-blue border-b border-slate-200 pb-1.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Job & Deployment Details
                    </h3>
                    
                    <div class="grid grid-cols-3 gap-1 text-xs">
                        <span class="text-slate-500 font-medium">Applied Post:</span>
                        <span class="col-span-2 font-bold text-brand-blue">{{ $circular?->title ?? 'Applied Circular' }}</span>

                        <span class="text-slate-500 font-medium">Destination:</span>
                        <span class="col-span-2 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded inline-block w-fit">{{ $circular?->country ?? 'Overseas' }}</span>

                        <span class="text-slate-500 font-medium">Application ID:</span>
                        <span class="col-span-2 font-mono font-semibold text-slate-700">#APP-{{ $application->id }}</span>

                        <span class="text-slate-500 font-medium">Status:</span>
                        <span class="col-span-2 font-bold text-amber-600 uppercase text-[11px]">Interview Called</span>
                    </div>
                </div>
            </div>

            <!-- Interview Schedule Highlight Box -->
            <div class="p-6 bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-300 rounded-2xl shadow-sm relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 opacity-10 text-amber-900 pointer-events-none">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
                </div>

                <div class="relative z-10 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-800">Interview / Test Date</span>
                        <p class="text-lg font-extrabold text-slate-900 mt-1">
                            {{ $application->interview_date ? $application->interview_date->format('d F, Y') : 'To Be Announced' }}
                        </p>
                        <p class="text-xs text-amber-700 font-semibold">{{ $application->interview_date ? $application->interview_date->format('l') : '' }}</p>
                    </div>

                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-800">Reporting Time</span>
                        <p class="text-lg font-extrabold text-slate-900 mt-1">
                            {{ $application->interview_time ?: '10:00 AM' }}
                        </p>
                        <p class="text-xs text-amber-700 font-medium">(Sharp / সময়মতো উপস্থিত থাকুন)</p>
                    </div>

                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-800">Reporting Venue</span>
                        <p class="text-xs font-bold text-slate-900 mt-1 leading-relaxed">
                            {{ $application->interview_venue ?: ($siteSettings?->address ?? 'Corporate Trade Test Center, Dhaka') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Candidate Instructions Checklist -->
            <div class="space-y-3">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Important Instructions for Candidate (প্রার্থীর জন্য জরুরি নির্দেশনাবলী)
                </h3>

                @if(!empty($application->interview_instructions))
                    <div class="p-3.5 bg-blue-50/70 rounded-xl border border-blue-200 text-xs text-blue-950 font-medium leading-relaxed">
                        {{ $application->interview_instructions }}
                    </div>
                @endif

                <ul class="space-y-2 text-xs text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-600 font-bold">1.</span>
                        <span>ইন্টারভিউ এবং ট্রেড টেস্টে উপস্থিত হওয়ার সময় এই প্রবেশপত্র (Admit Card) প্রিন্ট কপি সাথে নিয়ে আসবেন।</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-600 font-bold">2.</span>
                        <span>মূল পাসপোর্ট (Original Passport) এবং জাতীয় পরিচয়পত্র (NID) এর ফটোকপি সাথে রাখুন।</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-600 font-bold">3.</span>
                        <span>৪ কপি পাসপোর্ট সাইজ ল্যাব প্রিন্ট ছবি (সাদা বা নীল ব্যাকগ্রাউন্ড) সাথে আনতে হবে।</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-600 font-bold">4.</span>
                        <span>কাজের পূর্ব অভিজ্ঞতা ও শিক্ষাগত যোগ্যতার সনদের ফটোকপি সাথে রাখতে হবে।</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-600 font-bold">5.</span>
                        <span>নির্ধারিত সময়ের কমপক্ষে ৩০ মিনিট পূর্বে পরীক্ষা কেন্দ্রে রিপোর্ট করতে হবে।</span>
                    </li>
                </ul>
            </div>

            <!-- Signatures Section -->
            <div class="pt-8 grid grid-cols-2 gap-8 border-t border-slate-200">
                <div class="text-center">
                    <div class="h-12 border-b border-dashed border-slate-300 w-48 mx-auto"></div>
                    <p class="text-[11px] font-bold text-slate-700 mt-2">Candidate's Signature</p>
                    <p class="text-[10px] text-slate-400">(প্রার্থীর স্বাক্ষর)</p>
                </div>

                <div class="text-center">
                    <div class="h-12 border-b border-dashed border-slate-300 w-48 mx-auto flex items-end justify-center pb-1">
                        <span class="text-[10px] font-mono text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">SYSTEM VERIFIED</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-700 mt-2">Authorized Officer / Recruitment Desk</p>
                    <p class="text-[10px] text-slate-400">{{ $siteSettings?->site_name ?? 'Global Manpower Overseas Ltd.' }}</p>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="bg-slate-100 border-t border-slate-200 px-6 py-3 text-center text-[10px] text-slate-500 font-medium">
            This is a computer-generated interview slip issued under overseas recruitment license {{ $siteSettings?->bmet_license_no ?? 'RL-1452' }}. Helpline: {{ $siteSettings?->phone ?? '+880 2-9876543' }}
        </div>
    </div>

</body>
</html>
