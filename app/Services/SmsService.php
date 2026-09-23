<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\JobApplication;
use App\Models\SmsLog;
use App\Settings\SiteSettings;
use App\Settings\SmsSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected SmsSettings $settings;

    public function __construct(?SmsSettings $settings = null)
    {
        $this->settings = $settings ?? app(SmsSettings::class);
    }

    /**
     * Clean and format Bangladeshi mobile numbers (e.g. 017XXXXXXXX or 88017XXXXXXXX).
     */
    public function formatNumber(string $phone): string
    {
        // Remove spaces, hyphens, plus signs, brackets
        $cleaned = preg_replace('/[^\d]/', '', $phone);

        // If it starts with 880, strip to 01... or keep 01...
        if (str_starts_with($cleaned, '880') && strlen($cleaned) === 13) {
            $cleaned = substr($cleaned, 2); // converts 8801712345678 -> 01712345678
        }

        return $cleaned;
    }

    /**
     * Validate if the phone number is a valid 11-digit Bangladeshi mobile number.
     */
    public function isValidBdNumber(string $phone): bool
    {
        $formatted = $this->formatNumber($phone);
        return (bool) preg_match('/^01[3-9]\d{8}$/', $formatted);
    }

    /**
     * Send single SMS.
     *
     * @return array{success: bool, simulated: bool, response: string, log: SmsLog|null}
     */
    public function send(
        string $to,
        string $message,
        string $type = 'general',
        ?int $applicantId = null,
        ?int $jobApplicationId = null
    ): array {
        $formattedRecipient = $this->formatNumber($to);

        $isEnabled = $this->settings->is_enabled ?? true;
        $apiToken = trim($this->settings->api_token ?? '');
        $apiUrl = trim($this->settings->api_url ?? 'http://api.greenweb.com.bd/api.php');
        if (empty($apiUrl)) {
            $apiUrl = 'http://api.greenweb.com.bd/api.php';
        }

        // If gateway is disabled or no live token configured, operate in simulation mode
        if (!$isEnabled || empty($apiToken)) {
            $log = SmsLog::create([
                'recipient' => $formattedRecipient ?: $to,
                'message' => $message,
                'type' => $type,
                'status' => 'simulated',
                'gateway_response' => 'SMS Gateway is in Simulation Mode (No live API token or gateway disabled in settings).',
                'applicant_id' => $applicantId,
                'job_application_id' => $jobApplicationId,
            ]);

            return [
                'success' => true,
                'simulated' => true,
                'response' => 'Simulated: SMS logged without gateway dispatch.',
                'log' => $log,
            ];
        }

        try {
            // Prepare payload according to bdbulksms / Greenweb API standard
            $payload = [
                'token' => $apiToken,
                'to' => $formattedRecipient,
                'message' => $message,
            ];

            if (!empty($this->settings->sender_id)) {
                // If masking / sender ID is used
                $payload['senderid'] = $this->settings->sender_id;
            }

            // POST to gateway API
            $response = Http::asForm()->timeout(15)->post($apiUrl, $payload);
            $responseBody = $response->body();

            $isSuccessful = $response->successful() && (
                stripos($responseBody, 'SUCCESS') !== false ||
                stripos($responseBody, 'Ok') !== false ||
                stripos($responseBody, '200') !== false
            );

            $log = SmsLog::create([
                'recipient' => $formattedRecipient,
                'message' => $message,
                'type' => $type,
                'status' => $isSuccessful ? 'sent' : 'failed',
                'gateway_response' => $responseBody ?: 'HTTP Status: ' . $response->status(),
                'applicant_id' => $applicantId,
                'job_application_id' => $jobApplicationId,
            ]);

            return [
                'success' => $isSuccessful,
                'simulated' => false,
                'response' => $responseBody,
                'log' => $log,
            ];
        } catch (\Throwable $e) {
            Log::error('SMS Gateway Dispatch Error: ' . $e->getMessage(), [
                'recipient' => $formattedRecipient,
                'type' => $type,
            ]);

            $log = SmsLog::create([
                'recipient' => $formattedRecipient,
                'message' => $message,
                'type' => $type,
                'status' => 'failed',
                'gateway_response' => 'Exception: ' . $e->getMessage(),
                'applicant_id' => $applicantId,
                'job_application_id' => $jobApplicationId,
            ]);

            return [
                'success' => false,
                'simulated' => false,
                'response' => $e->getMessage(),
                'log' => $log,
            ];
        }
    }

    /**
     * Generate and dispatch a secure 6-digit OTP to an applicant.
     */
    public function sendOtp(Applicant $applicant): array
    {
        $otp = (string) random_int(100000, 999999);
        $expiryMinutes = $this->settings->otp_expiry_minutes ?: 5;

        $applicant->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes($expiryMinutes),
        ]);

        $siteName = 'Our Agency';
        try {
            $siteSettings = app(SiteSettings::class);
            if (!empty($siteSettings->site_name)) {
                $siteName = $siteSettings->site_name;
            }
        } catch (\Throwable) {}

        $template = $this->settings->otp_template ?: 'Your OTP for {site_name} is: {otp}. Valid for {expiry} minutes.';
        $message = str_replace(
            ['{site_name}', '{otp}', '{expiry}'],
            [$siteName, $otp, $expiryMinutes],
            $template
        );

        $phone = $applicant->phone ?: $applicant->mobile_no;

        return $this->send(
            to: $phone,
            message: $message,
            type: 'otp',
            applicantId: $applicant->id
        );
    }

    /**
     * Dispatch Interview / Admit Card SMS to an applicant for a job application.
     */
    public function sendInterviewSms(JobApplication $application, array $details): array
    {
        $applicant = $application->applicant;
        $jobCircular = $application->jobCircular;

        $name = $applicant?->name ?? 'Candidate';
        $jobTitle = $jobCircular?->title ?? 'Applied Position';
        $date = !empty($details['interview_date']) ? date('d-M-Y', strtotime($details['interview_date'])) : 'TBA';
        $time = $details['interview_time'] ?? 'TBA';
        $venue = $details['interview_venue'] ?? 'Head Office';
        $cardLink = url('/interview-card/' . ($application->admit_card_token ?: 'preview'));

        $template = !empty($details['custom_message']) 
            ? $details['custom_message'] 
            : ($this->settings->interview_template ?: 'Dear {name}, You are shortlisted for {job_title}. Interview Date: {date}, Time: {time}, Venue: {venue}. Admit Card: {card_link}');

        $message = str_replace(
            ['{name}', '{job_title}', '{date}', '{time}', '{venue}', '{card_link}'],
            [$name, $jobTitle, $date, $time, $venue, $cardLink],
            $template
        );

        $phone = $applicant?->phone ?: $applicant?->mobile_no;

        return $this->send(
            to: $phone,
            message: $message,
            type: 'interview_card',
            applicantId: $applicant?->id,
            jobApplicationId: $application->id
        );
    }

    /**
     * Query remaining balance from Greenweb / bdbulksms gateway API.
     */
    public function checkBalance(): array
    {
        $apiToken = trim($this->settings->api_token ?? '');
        $apiUrl = trim($this->settings->api_url ?? 'http://api.greenweb.com.bd/api.php');

        if (empty($apiToken)) {
            return [
                'success' => false,
                'balance' => null,
                'message' => 'Please configure your SMS API Token in Settings first.',
            ];
        }

        // Greenweb official balance endpoint is g_api.php
        $baseUrl = preg_replace('/(\/api\.php|\/g_api\.php|\/api)?$/i', '', $apiUrl);
        if (empty($baseUrl)) {
            $baseUrl = 'http://api.greenweb.com.bd';
        }

        $endpointsToTry = [
            $baseUrl . '/g_api.php',
            'http://api.greenweb.com.bd/g_api.php',
            'http://api.bdbulksms.net/g_api.php',
            $baseUrl . '/api.php',
        ];

        $lastError = 'Unable to connect to balance API.';

        foreach ($endpointsToTry as $endpoint) {
            try {
                $response = Http::timeout(10)->get($endpoint, [
                    'token' => $apiToken,
                    'balance' => 'true',
                    'json' => '',
                ]);

                if ($response->successful()) {
                    $body = trim($response->body());
                    if (!empty($body) && stripos($body, '404') === false && stripos($body, 'not found') === false) {
                        // Check if JSON response
                        $json = json_decode($body, true);
                        if (is_array($json)) {
                            // Extract balance or message
                            $balanceVal = $json[0]['balance'] ?? $json['balance'] ?? ($json[0]['response'] ?? json_encode($json));
                            return [
                                'success' => true,
                                'balance' => (string) $balanceVal,
                                'message' => 'Balance fetched successfully.',
                            ];
                        }

                        // Plain text / integer balance response (e.g. "540" or "Balance: 540")
                        return [
                            'success' => true,
                            'balance' => strip_tags($body),
                            'message' => 'Balance checked successfully.',
                        ];
                    }
                }
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        return [
            'success' => false,
            'balance' => null,
            'message' => 'Unable to fetch balance from gateway. (' . $lastError . ')',
        ];
    }
}
