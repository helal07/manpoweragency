<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 sticky top-0">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-500 hover:text-slate-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
        </button>
        <h1 class="text-xl font-bold text-slate-800 hidden sm:block">
            @yield('header_title', 'Dashboard')
        </h1>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('home') }}" target="_blank" class="text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors hidden sm:inline-block">
            View Public Site &rarr;
        </a>
        
        <div class="relative" x-data="{ userMenuOpen: false }">
            <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex items-center gap-2 focus:outline-none">
                <div class="w-9 h-9 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-700 font-bold overflow-hidden">
                    @if(auth()->user()->getFirstMediaUrl('avatar'))
                        <img src="{{ auth()->user()->getFirstMediaUrl('avatar') }}" class="w-full h-full object-cover" alt="Avatar">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="hidden md:block text-left">
                    <div class="text-sm font-semibold text-slate-700 leading-none mb-1">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500 leading-none">Applicant</div>
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            
            <div x-show="userMenuOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50" style="display: none;">
                <div class="px-4 py-2 border-b border-slate-100 md:hidden">
                    <div class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ auth()->user()->email }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile Settings</a>
                <a href="{{ route('security.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Security</a>
                <div class="border-t border-slate-100 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</header>
