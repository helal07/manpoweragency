<footer style="background-color: rgb(26, 58, 82) !important; color: #FFFFFF !important; border-top: 1px solid rgba(255, 255, 255, 0.15) !important;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Col 1: About Teaser & Licensing -->
            <div class="space-y-4 md:col-span-1">
                <div class="flex items-center space-x-3">
                    @if(!empty($siteSettings['logo_url']))
                        <img src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] }}" class="h-10 w-auto">
                    @else
                        <div class="w-9 h-9 rounded-md flex items-center justify-center font-bold text-lg text-slate-900" style="background-color: #F59E0B !important;">
                            {{ strtoupper(substr($siteSettings['site_name'] ?? 'M', 0, 1)) }}
                        </div>
                    @endif
                    <span class="text-lg font-bold tracking-tight" style="color: #FFFFFF !important;">
                        {{ $siteSettings['site_name'] ?? 'Global Manpower Overseas' }}
                    </span>
                </div>
                <p class="text-xs leading-relaxed" style="color: rgba(255, 255, 255, 0.85) !important;">
                    {{ Str::limit($siteSettings['about_teaser'] ?? 'Government-approved recruiting agency supplying skilled, semi-skilled, and professional manpower to international clients.', 140) }}
                </p>
                <div class="pt-2 text-xs font-semibold" style="color: #F59E0B !important;">
                    License No: {{ $siteSettings['bmet_license_no'] ?? 'RL-1452' }}
                </div>
            </div>

            <!-- Col 2: Navigation Links -->
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4 pl-2" style="color: #FFFFFF !important; border-left: 3px solid #F59E0B !important;">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/') }}" class="transition-colors hover:text-[#F59E0B]" style="color: rgba(255, 255, 255, 0.9) !important;">Home</a></li>
                    <li><a href="{{ url('/about') }}" class="transition-colors hover:text-[#F59E0B]" style="color: rgba(255, 255, 255, 0.9) !important;">About Us</a></li>
                    <li><a href="{{ url('/services') }}" class="transition-colors hover:text-[#F59E0B]" style="color: rgba(255, 255, 255, 0.9) !important;">Our Services</a></li>
                    <li><a href="{{ url('/clients') }}" class="transition-colors hover:text-[#F59E0B]" style="color: rgba(255, 255, 255, 0.9) !important;">Global Clients</a></li>
                    <li><a href="{{ url('/job-circulars') }}" class="transition-colors hover:text-[#F59E0B]" style="color: rgba(255, 255, 255, 0.9) !important;">Job Circulars</a></li>
                    <li><a href="{{ url('/notices') }}" class="transition-colors hover:text-[#F59E0B]" style="color: rgba(255, 255, 255, 0.9) !important;">Notice Board</a></li>
                </ul>
            </div>

            <!-- Col 3: Services Summary -->
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4 pl-2" style="color: #FFFFFF !important; border-left: 3px solid #F59E0B !important;">Recruitment Sectors</h4>
                <ul class="space-y-2 text-sm" style="color: rgba(255, 255, 255, 0.85) !important;">
                    <li>Construction & Engineering</li>
                    <li>Healthcare & Medical Staff</li>
                    <li>Hospitality & Catering</li>
                    <li>Manufacturing & Logistics</li>
                    <li>Visa & Migration Assistance</li>
                </ul>
            </div>

            <!-- Col 4: Contact Details -->
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4 pl-2" style="color: #FFFFFF !important; border-left: 3px solid #F59E0B !important;">Head Office</h4>
                <div class="space-y-3 text-sm" style="color: #FFFFFF !important;">
                    <p class="flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" style="color: #F59E0B !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $siteSettings['company_address'] ?? 'Banani, Dhaka, Bangladesh' }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" style="color: #F59E0B !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>{{ $siteSettings['company_phone'] ?? '+880 2-9876543' }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" style="color: #F59E0B !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>{{ $siteSettings['company_email'] ?? 'info@globalmanpower.com' }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t flex flex-col md:flex-row justify-between items-center text-xs" style="border-color: rgba(255, 255, 255, 0.15) !important; color: rgba(255, 255, 255, 0.8) !important;">
            <p>{{ $siteSettings['footer_copyright'] ?? '© 2026 Global Manpower Overseas Ltd. All Rights Reserved.' }}</p>
            <div class="mt-4 md:mt-0 flex space-x-6">
                <a href="{{ url('/admin') }}" class="transition-colors hover:text-[#F59E0B]" style="color: #FFFFFF !important;">Staff Portal</a>
            </div>
        </div>
    </div>
</footer>
