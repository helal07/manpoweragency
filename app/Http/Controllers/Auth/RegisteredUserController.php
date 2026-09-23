<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Applicant::class],
            'phone' => ['required', 'string', 'max:20'],
            'nid_passport' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $smsService = app(\App\Services\SmsService::class);
        $formattedPhone = $smsService->formatNumber($request->phone);

        if (!$smsService->isValidBdNumber($formattedPhone)) {
            return back()->withInput()->withErrors([
                'phone' => 'Please provide a valid 11-digit Bangladeshi mobile number (e.g. 017XXXXXXXX).',
            ]);
        }

        // Check if phone already registered
        $existingPhone = Applicant::where('phone', $formattedPhone)
            ->orWhere('mobile_no', $formattedPhone)
            ->first();

        if ($existingPhone) {
            return back()->withInput()->withErrors([
                'phone' => 'This mobile number is already registered. Please sign in or use another number.',
            ]);
        }

        $applicant = Applicant::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $formattedPhone,
            'mobile_no' => $formattedPhone,
            'nid_passport' => $request->nid_passport,
            'password' => Hash::make($request->password),
            'phone_verified_at' => null,
            'email_verified_at' => null,
        ]);

        event(new Registered($applicant));

        // Generate and send OTP via SMS Gateway
        $smsResult = $smsService->sendOtp($applicant);

        session(['otp_verify_applicant_id' => $applicant->id]);

        $msg = 'Account created! Please enter the 6-digit OTP code sent to your mobile phone (' . $formattedPhone . ') to activate your account.';
        if (!empty($smsResult['simulated'])) {
            $msg .= ' [Simulation Mode: OTP is ' . $applicant->otp_code . ']';
        }

        return redirect()->route('otp.verify.show')->with('status', $msg);
    }
}
