<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = $this->input('login');
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Clean phone number format if phone was entered
        if ($fieldType === 'phone') {
            $cleaned = preg_replace('/[^\d]/', '', $loginInput);
            if (str_starts_with($cleaned, '880') && strlen($cleaned) === 13) {
                $cleaned = substr($cleaned, 2);
            }
            $loginInput = $cleaned;
        }

        $credentials = [
            $fieldType => $loginInput,
            'password' => $this->input('password'),
        ];

        if (! Auth::guard('web')->attempt($credentials, $this->boolean('remember'))) {
            // Also try matching 'mobile_no' if phone field
            if ($fieldType === 'phone') {
                $credentialsAlt = [
                    'mobile_no' => $loginInput,
                    'password' => $this->input('password'),
                ];
                if (! Auth::guard('web')->attempt($credentialsAlt, $this->boolean('remember'))) {
                    RateLimiter::hit($this->throttleKey());

                    throw ValidationException::withMessages([
                        'login' => trans('auth.failed'),
                    ]);
                }
            } else {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'login' => trans('auth.failed'),
                ]);
            }
        }

        // Check if phone is verified
        $user = Auth::guard('web')->user();
        if ($user && empty($user->phone_verified_at)) {
            session(['otp_verify_applicant_id' => $user->id]);
            app(\App\Services\SmsService::class)->sendOtp($user);
            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'login' => 'Your phone number is not verified yet. A new OTP has been sent to your mobile phone. Please verify your account.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
