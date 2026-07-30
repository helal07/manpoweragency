<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Applicant Portal Sign In</h2>
        <p class="text-xs text-[#475569] mt-1">Access your overseas job applications & visa status</p>
    </div>

    <!-- Success Message Alert -->
    @if (session('status'))
        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-[#10B981] text-xs font-semibold flex items-start gap-2.5 shadow-sm">
            <svg class="w-4 h-4 text-[#10B981] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Invalid Credentials Error Alert -->
    @if ($errors->any())
        <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-start gap-2.5 shadow-sm">
            <svg class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="font-bold">Invalid Login Credentials!</p>
                <p class="font-normal text-red-700 mt-0.5">Please check your email address and password, then try again.</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="applicant@example.com"
                class="w-full px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E2E8F0] text-[#0F172A] placeholder-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 text-sm transition-all shadow-sm">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••"
                class="w-full px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E2E8F0] text-[#0F172A] placeholder-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 text-sm transition-all shadow-sm">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-600" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-[#E2E8F0] bg-[#FFFFFF] text-[#0F172A] focus:ring-[#1E3A8A]/20">
                <span class="ms-2 text-xs text-[#475569] font-medium">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-[#1E3A8A] hover:text-[#0F172A] transition-colors font-bold underline" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- 10% Accent CTA Button: Warm Amber Gold #F59E0B -->
        <div class="mt-6">
            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#F59E0B] hover:bg-[#D97706] text-[#0F172A] font-extrabold text-sm shadow-lg shadow-[#F59E0B]/25 transition-all flex items-center justify-center gap-2">
                <span>Sign In to Dashboard</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>

        <div class="mt-6 text-center border-t border-[#E2E8F0] pt-4">
            <p class="text-xs text-[#475569] font-medium">
                Don't have an account yet?
                <a href="{{ route('register') }}" class="text-[#1E3A8A] font-extrabold hover:text-[#0F172A] transition-colors ms-1 underline">Register Now</a>
            </p>
        </div>
    </form>
</x-guest-layout>
