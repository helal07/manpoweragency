<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-14 h-14 bg-amber-50 rounded-2xl border border-amber-200 text-[#F59E0B] flex items-center justify-center mx-auto mb-3 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Verify Mobile Number</h2>
        <p class="text-xs text-[#475569] mt-1">
            We sent a 6-digit verification code to
            <span class="font-bold text-[#0F172A]">{{ $maskedPhone }}</span>
        </p>
    </div>

    <!-- Success Message Alert -->
    @if (session('status'))
        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-start gap-2.5 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Simulation Mode Helper Badge if live token not yet added -->
    @if (!empty($simulatedOtp))
        <div class="mb-5 p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs flex items-center justify-between">
            <span class="font-medium">💡 <strong>Simulation Mode OTP:</strong> <code class="font-mono bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold">{{ $simulatedOtp }}</code></span>
            <button type="button" onclick="document.getElementById('otp').value='{{ $simulatedOtp }}'" class="text-xs text-blue-700 font-bold underline hover:text-blue-900">Auto-fill</button>
        </div>
    @endif

    <!-- OTP Input Form -->
    <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-5">
        @csrf

        <div>
            <label for="otp" class="block text-center text-xs font-bold uppercase tracking-wider text-[#0F172A] mb-2">
                Enter 6-Digit OTP Code
            </label>
            <div class="relative max-w-[280px] mx-auto">
                <input id="otp" type="text" name="otp" inputmode="numeric" pattern="[0-9]*" maxlength="6" required autofocus
                    placeholder="••••••"
                    value="{{ old('otp') }}"
                    class="w-full text-center text-2xl font-mono font-extrabold tracking-[0.5em] px-4 py-3 rounded-xl bg-[#FFFFFF] border-2 border-[#CBD5E1] text-[#0F172A] placeholder-slate-300 focus:border-[#1E3A8A] focus:ring-4 focus:ring-[#1E3A8A]/15 transition-all shadow-sm">
            </div>
            <x-input-error :messages="$errors->get('otp')" class="mt-2 text-center text-xs text-red-600 font-semibold" />
        </div>

        <div>
            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#F59E0B] hover:bg-[#D97706] text-[#0F172A] font-extrabold text-sm shadow-lg shadow-[#F59E0B]/25 transition-all flex items-center justify-center gap-2">
                <span>Verify & Activate Account</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>

    <!-- Resend OTP Section -->
    <div class="mt-6 pt-5 border-t border-[#E2E8F0] text-center">
        <p class="text-xs text-[#64748B] mb-2">Didn't receive the SMS verification code?</p>
        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <button id="resendBtn" type="submit" class="text-xs font-extrabold text-[#1E3A8A] hover:text-[#0F172A] transition-colors underline disabled:opacity-50 disabled:cursor-not-allowed">
                Resend OTP via SMS
            </button>
        </form>
    </div>
</x-guest-layout>
