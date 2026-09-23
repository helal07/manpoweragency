<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $applicant = $this->resolveApplicant($request);

        if (!$applicant) {
            return redirect()->route('register')->withErrors([
                'phone' => 'No pending verification session found. Please register or sign in.',
            ]);
        }

        if ($applicant->isPhoneVerified()) {
            if (!Auth::guard('web')->check()) {
                Auth::guard('web')->login($applicant);
            }
            return redirect()->route('dashboard');
        }

        $maskedPhone = $this->maskPhoneNumber($applicant->phone ?: $applicant->mobile_no);

        return view('auth.verify-otp', [
            'applicant' => $applicant,
            'maskedPhone' => $maskedPhone,
            'simulatedOtp' => app(\App\Settings\SmsSettings::class)->is_enabled && !empty(app(\App\Settings\SmsSettings::class)->api_token) ? null : $applicant->otp_code,
        ]);
    }

    /**
     * Handle the OTP verification request.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $applicant = $this->resolveApplicant($request);

        if (!$applicant) {
            return redirect()->route('register')->withErrors([
                'phone' => 'Session expired. Please register or sign in again.',
            ]);
        }

        $inputOtp = trim($request->otp);

        if (empty($applicant->otp_code) || $applicant->otp_code !== $inputOtp) {
            return back()->withErrors([
                'otp' => 'Invalid OTP code! Please check your SMS and enter the correct 6-digit code.',
            ]);
        }

        if ($applicant->otp_expires_at && $applicant->otp_expires_at->isPast()) {
            return back()->withErrors([
                'otp' => 'This OTP code has expired. Please click "Resend OTP" to receive a new one.',
            ]);
        }

        // Mark applicant as verified
        $applicant->update([
            'phone_verified_at' => now(),
            'email_verified_at' => $applicant->email_verified_at ?: now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        session()->forget('otp_verify_applicant_id');

        Auth::guard('web')->login($applicant);

        return redirect()->route('dashboard')->with('status', '🎉 Mobile number verified successfully! Welcome to your overseas career portal.');
    }

    /**
     * Resend OTP code to the applicant's mobile number.
     */
    public function resend(Request $request, SmsService $smsService): RedirectResponse
    {
        $applicant = $this->resolveApplicant($request);

        if (!$applicant) {
            return redirect()->route('register')->withErrors([
                'phone' => 'Session expired. Please register or sign in.',
            ]);
        }

        // Cooldown check (60 seconds)
        $lastSent = session('otp_last_sent_at');
        if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
            $remaining = 60 - now()->diffInSeconds($lastSent);
            return back()->withErrors([
                'otp' => "Please wait {$remaining} seconds before requesting another OTP.",
            ]);
        }

        $result = $smsService->sendOtp($applicant);
        session(['otp_last_sent_at' => now()]);

        $msg = 'A new 6-digit OTP code has been dispatched to your mobile number.';
        if (!empty($result['simulated'])) {
            $msg .= ' [Simulation Mode: New OTP is ' . $applicant->otp_code . ']';
        }

        return back()->with('status', $msg);
    }

    /**
     * Resolve applicant from session or current auth guard.
     */
    protected function resolveApplicant(Request $request): ?Applicant
    {
        if (Auth::guard('web')->check()) {
            /** @var Applicant $user */
            $user = Auth::guard('web')->user();
            return $user;
        }

        $applicantId = session('otp_verify_applicant_id');
        if ($applicantId) {
            return Applicant::find($applicantId);
        }

        return null;
    }

    /**
     * Mask mobile number for privacy display (e.g. 017****5678).
     */
    protected function maskPhoneNumber(?string $phone): string
    {
        if (!$phone) {
            return 'N/A';
        }
        $len = strlen($phone);
        if ($len <= 5) {
            return $phone;
        }
        return substr($phone, 0, 3) . '****' . substr($phone, -4);
    }
}
