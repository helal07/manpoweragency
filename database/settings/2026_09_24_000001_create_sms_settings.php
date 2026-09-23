<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sms.is_enabled', true);
        $this->migrator->add('sms.api_url', 'http://api.greenweb.com.bd/api.php');
        $this->migrator->add('sms.api_token', '');
        $this->migrator->add('sms.sender_id', '');
        $this->migrator->add('sms.otp_template', 'Your OTP for {site_name} is: {otp}. Valid for {expiry} minutes. Do not share with anyone.');
        $this->migrator->add('sms.interview_template', 'Dear {name}, You are shortlisted for {job_title}. Interview Date: {date}, Time: {time}, Venue: {venue}. Admit Card: {card_link}');
        $this->migrator->add('sms.otp_expiry_minutes', 5);
    }
};
