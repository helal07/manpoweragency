<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SmsSettings extends Settings
{
    public bool $is_enabled;
    public ?string $api_url;
    public ?string $api_token;
    public ?string $sender_id;
    public ?string $otp_template;
    public ?string $interview_template;
    public int $otp_expiry_minutes;

    public static function group(): string
    {
        return 'sms';
    }
}
