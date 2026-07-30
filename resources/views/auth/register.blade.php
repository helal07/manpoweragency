<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Create Applicant Account</h2>
        <p class="text-xs text-[#475569] mt-1">Apply for overseas job vacancies & track visa status</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Full Name -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="e.g. Md. Tanvir Ahmed"
                class="w-full px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E2E8F0] text-[#0F172A] placeholder-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 text-sm transition-all shadow-sm">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-600" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="applicant@example.com"
                class="w-full px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E2E8F0] text-[#0F172A] placeholder-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 text-sm transition-all shadow-sm">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-1">Phone Number</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                placeholder="+880 1700-000000"
                class="w-full px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E2E8F0] text-[#0F172A] placeholder-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 text-sm transition-all shadow-sm">
            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs text-red-600" />
        </div>

        <!-- NID / Passport No. -->
        <div class="mt-4">
            <label for="nid_passport" class="block text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-1">NID or Passport No.</label>
            <input id="nid_passport" type="text" name="nid_passport" value="{{ old('nid_passport') }}"
                placeholder="e.g. A01234567 / 1995123456"
                class="w-full px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E2E8F0] text-[#0F172A] placeholder-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 text-sm transition-all shadow-sm">
            <x-input-error :messages="$errors->get('nid_passport')" class="mt-2 text-xs text-red-600" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E2E8F0] text-[#0F172A] placeholder-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 text-sm transition-all shadow-sm">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E2E8F0] text-[#0F172A] placeholder-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 text-sm transition-all shadow-sm">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-600" />
        </div>

        <!-- 10% Accent CTA Button: Warm Amber Gold #F59E0B -->
        <div class="mt-6">
            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#F59E0B] hover:bg-[#D97706] text-[#0F172A] font-extrabold text-sm shadow-lg shadow-[#F59E0B]/25 transition-all flex items-center justify-center gap-2">
                <span>Create Applicant Account</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>

        <div class="mt-6 text-center border-t border-[#E2E8F0] pt-4">
            <p class="text-xs text-[#475569] font-medium">
                Already registered?
                <a href="{{ route('login') }}" class="text-[#1E3A8A] font-extrabold hover:text-[#0F172A] transition-colors ms-1 underline">Sign In Here</a>
            </p>
        </div>
    </form>
</x-guest-layout>
