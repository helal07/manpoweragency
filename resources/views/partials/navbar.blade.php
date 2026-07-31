<style>
    /* Strict Responsive Visibility: 3-line icon ONLY on mobile, Flex links ONLY on desktop */
    @media (min-width: 1024px) {
        .mobile-hamburger-toggle {
            display: none !important;
        }
        .mobile-menu-container {
            display: none !important;
        }
        .desktop-nav-menu {
            display: flex !important;
        }
        .desktop-cta-btn {
            display: flex !important;
        }
    }
    @media (max-width: 1023px) {
        .mobile-hamburger-toggle {
            display: flex !important;
        }
        .desktop-nav-menu {
            display: none !important;
        }
        .desktop-cta-btn {
            display: none !important;
        }
    }
</style>

<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 shadow-2xl" style="background-color: rgb(26, 58, 82) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;">
    <!-- Top Information & License Bar -->
    <div style="background-color: rgba(15, 38, 56, 0.9) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important; font-size: 0.75rem; padding-top: 0.5rem; padding-bottom: 0.5rem; color: #FFFFFF;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="flex items-center space-x-6">
                <span class="flex items-center gap-1.5" style="color: #F59E0B; font-weight: 600;">
                    <svg class="w-3.5 h-3.5" style="color: #F59E0B;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span style="color: #FFFFFF !important;">{{ $siteSettings['company_phone'] ?? '+880 2-9876543' }}</span>
                </span>
                <span class="flex items-center gap-1.5" style="color: #F59E0B; font-weight: 600;">
                    <svg class="w-3.5 h-3.5" style="color: #F59E0B;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span style="color: #FFFFFF !important;">{{ $siteSettings['company_email'] ?? 'info@globalmanpower.com' }}</span>
                </span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full" style="background-color: rgba(0, 0, 0, 0.25); border: 1px solid #F59E0B; color: #F59E0B; font-weight: 700;">
                    <span class="w-2 h-2 rounded-full animate-pulse" style="background-color: #F59E0B;"></span>
                    Govt. License: {{ $siteSettings['bmet_license_no'] ?? 'RL-1452' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar (rgb(26, 58, 82) Background) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="background-color: rgb(26, 58, 82) !important;">
        <div class="flex items-center justify-between h-20">
            
            <!-- Logo Area: Pure White Text with Warm Amber Gold Accent -->
            <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                @if(!empty($siteSettings['logo_url']))
                    <img src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] ?? 'Company Logo' }}" class="h-11 w-auto object-contain">
                @else
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center font-extrabold text-xl shadow-md transition-all transform group-hover:scale-105" style="background-color: #F59E0B !important; color: rgb(26, 58, 82) !important;">
                        {{ strtoupper(substr($siteSettings['site_name'] ?? 'M', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <span class="text-xl font-extrabold tracking-tight block leading-tight transition-colors" style="color: #FFFFFF !important;">
                        {{ $siteSettings['site_name'] ?? 'Global Manpower Overseas' }}
                    </span>
                    <span class="text-xs block font-medium" style="color: rgba(255, 255, 255, 0.8) !important;">
                        {{ $siteSettings['site_tagline'] ?? 'Recruitment & Overseas Employment Agency' }}
                    </span>
                </div>
            </a>

            <!-- Desktop Horizontal Navigation Links (HIDDEN on mobile, FLEXED on desktop >= 1024px) -->
            <nav class="desktop-nav-menu items-center space-x-2">
                <a href="{{ url('/') }}" class="relative px-3.5 py-2 text-sm font-semibold transition-colors group" style="color: {{ request()->is('/') ? '#F59E0B' : '#FFFFFF' }} !important;">
                    <span>{{ $siteSettings['nav_home_label'] ?? 'Home' }}</span>
                    <span class="absolute bottom-0 left-3.5 right-3.5 h-0.5 transition-all transform {{ request()->is('/') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}" style="background-color: #F59E0B !important;"></span>
                </a>

                <a href="{{ url('/about') }}" class="relative px-3.5 py-2 text-sm font-semibold transition-colors group" style="color: {{ request()->is('about') ? '#F59E0B' : '#FFFFFF' }} !important;">
                    <span>{{ $siteSettings['nav_about_label'] ?? 'About' }}</span>
                    <span class="absolute bottom-0 left-3.5 right-3.5 h-0.5 transition-all transform {{ request()->is('about') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}" style="background-color: #F59E0B !important;"></span>
                </a>

                <a href="{{ url('/clients') }}" class="relative px-3.5 py-2 text-sm font-semibold transition-colors group" style="color: {{ request()->is('clients') ? '#F59E0B' : '#FFFFFF' }} !important;">
                    <span>{{ $siteSettings['nav_clients_label'] ?? 'Clients' }}</span>
                    <span class="absolute bottom-0 left-3.5 right-3.5 h-0.5 transition-all transform {{ request()->is('clients') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}" style="background-color: #F59E0B !important;"></span>
                </a>
                
                <a href="{{ url('/services') }}" class="relative px-3.5 py-2 text-sm font-semibold transition-colors group" style="color: {{ request()->is('services') ? '#F59E0B' : '#FFFFFF' }} !important;">
                    <span>{{ $siteSettings['nav_services_label'] ?? 'Services' }}</span>
                    <span class="absolute bottom-0 left-3.5 right-3.5 h-0.5 transition-all transform {{ request()->is('services') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}" style="background-color: #F59E0B !important;"></span>
                </a>

                <a href="{{ url('/job-circulars') }}" class="relative px-3.5 py-2 text-sm font-semibold transition-colors group" style="color: {{ request()->is('job-circulars*') ? '#F59E0B' : '#FFFFFF' }} !important;">
                    <span>{{ $siteSettings['nav_circulars_label'] ?? 'Job Circular' }}</span>
                    <span class="absolute bottom-0 left-3.5 right-3.5 h-0.5 transition-all transform {{ request()->is('job-circulars*') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}" style="background-color: #F59E0B !important;"></span>
                </a>

                <a href="{{ url('/notices') }}" class="relative px-3.5 py-2 text-sm font-semibold transition-colors group" style="color: {{ request()->is('notices*') ? '#F59E0B' : '#FFFFFF' }} !important;">
                    <span>{{ $siteSettings['nav_notices_label'] ?? 'Notice' }}</span>
                    <span class="absolute bottom-0 left-3.5 right-3.5 h-0.5 transition-all transform {{ request()->is('notices*') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}" style="background-color: #F59E0B !important;"></span>
                </a>
            </nav>

            <!-- Desktop CTA Button (HIDDEN on mobile, FLEXED on desktop >= 1024px) -->
            <div class="desktop-cta-btn items-center space-x-3">
                @auth('web')
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl font-extrabold text-sm shadow-lg hover:scale-105 active:scale-95 transition-all transform flex items-center gap-2" style="background-color: #F59E0B !important; color: rgb(26, 58, 82) !important;">
                        <span>Applicant Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl font-extrabold text-sm shadow-lg hover:scale-105 active:scale-95 transition-all transform flex items-center gap-2" style="background-color: #F59E0B !important; color: rgb(26, 58, 82) !important; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3) !important;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>{{ $siteSettings['nav_login_label'] ?? 'Login' }}</span>
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger 3-Line Toggle Button (STRICTLY HIDDEN on desktop >= 1024px, ONLY VISIBLE on mobile < 1024px) -->
            <div class="mobile-hamburger-toggle items-center space-x-2">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2.5 rounded-xl transition-colors" style="color: #FFFFFF !important; background-color: rgba(0, 0, 0, 0.2) !important;" aria-expanded="false">
                    <span class="sr-only">Toggle Main Menu</span>
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Slide-Over Menu (ONLY on mobile screens < 1024px) -->
    <div x-show="mobileMenuOpen" x-cloak 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 -translate-y-2" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100 translate-y-0" 
         x-transition:leave-end="opacity-0 -translate-y-2" 
         class="mobile-menu-container flex flex-col px-4 pt-3 pb-6 space-y-3 shadow-2xl absolute w-full left-0 top-full"
         style="background-color: rgb(26, 58, 82) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;">
        
        <div class="space-y-1 w-full">
            <a href="{{ url('/') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold transition-colors" style="color: {{ request()->is('/') ? '#F59E0B' : '#FFFFFF' }} !important; background-color: {{ request()->is('/') ? 'rgba(0,0,0,0.2)' : 'transparent' }} !important;">
                {{ $siteSettings['nav_home_label'] ?? 'Home' }}
            </a>
            <a href="{{ url('/about') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold transition-colors" style="color: {{ request()->is('about') ? '#F59E0B' : '#FFFFFF' }} !important; background-color: {{ request()->is('about') ? 'rgba(0,0,0,0.2)' : 'transparent' }} !important;">
                {{ $siteSettings['nav_about_label'] ?? 'About' }}
            </a>
            <a href="{{ url('/clients') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold transition-colors" style="color: {{ request()->is('clients') ? '#F59E0B' : '#FFFFFF' }} !important; background-color: {{ request()->is('clients') ? 'rgba(0,0,0,0.2)' : 'transparent' }} !important;">
                {{ $siteSettings['nav_clients_label'] ?? 'Clients' }}
            </a>
            <a href="{{ url('/services') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold transition-colors" style="color: {{ request()->is('services') ? '#F59E0B' : '#FFFFFF' }} !important; background-color: {{ request()->is('services') ? 'rgba(0,0,0,0.2)' : 'transparent' }} !important;">
                {{ $siteSettings['nav_services_label'] ?? 'Services' }}
            </a>
            <a href="{{ url('/job-circulars') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold transition-colors" style="color: {{ request()->is('job-circulars*') ? '#F59E0B' : '#FFFFFF' }} !important; background-color: {{ request()->is('job-circulars*') ? 'rgba(0,0,0,0.2)' : 'transparent' }} !important;">
                {{ $siteSettings['nav_circulars_label'] ?? 'Job Circular' }}
            </a>
            <a href="{{ url('/notices') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold transition-colors" style="color: {{ request()->is('notices*') ? '#F59E0B' : '#FFFFFF' }} !important; background-color: {{ request()->is('notices*') ? 'rgba(0,0,0,0.2)' : 'transparent' }} !important;">
                {{ $siteSettings['nav_notices_label'] ?? 'Notice' }}
            </a>
        </div>

        <div class="pt-4 flex flex-col gap-2 w-full" style="border-top: 1px solid rgba(255, 255, 255, 0.15) !important;">
            @auth('web')
                <a href="{{ url('/dashboard') }}" class="w-full py-3 px-4 rounded-xl font-bold text-sm text-center transition-all" style="background-color: #F59E0B !important; color: rgb(26, 58, 82) !important;">
                    Applicant Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full py-3 px-4 rounded-xl font-bold text-sm text-center transition-all" style="background-color: #F59E0B !important; color: rgb(26, 58, 82) !important;">
                    {{ $siteSettings['nav_login_label'] ?? 'Login' }}
                </a>
            @endauth
        </div>
    </div>
</header>
