<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.about_banner_title', 'About Global Manpower Overseas Ltd.');
        $this->migrator->add('site.about_banner_subtitle', 'Over 18 years of pioneering excellence in overseas human resource recruitment and ethical labor migration.');
        $this->migrator->add('site.about_story_title', 'Who We Are');
        $this->migrator->add('site.about_story_p1', 'Global Manpower Overseas Ltd. is a premier government-licensed overseas recruitment agency in Bangladesh (RL-1452). Specializing in sourcing, trade-testing, and deploying skilled, semi-skilled, and professional personnel across the Middle East, Southeast Asia, and Europe.');
        $this->migrator->add('site.about_story_p2', 'With our state-of-the-art Trade Testing Center and experienced management team, we bridge the gap between Bangladeshi talent and international corporate demand, upholding the highest standards of worker welfare, legal compliance, and operational efficiency.');
        $this->migrator->add('site.about_mission_title', 'Our Mission');
        $this->migrator->add('site.about_mission_statement', 'To empower Bangladeshi workforce with dignified, legal overseas employment while providing top-tier labor solutions to global employers.');
        $this->migrator->add('site.about_vision_title', 'Our Vision');
        $this->migrator->add('site.about_vision_statement', 'To be the most ethical, transparent, and preferred overseas manpower consultancy in South Asia.');
        $this->migrator->add('site.about_accreditation_title', 'Government Accreditation');
        $this->migrator->add('site.about_accreditation_1', 'Ministry of Expatriates\' Welfare & Overseas Employment Approved');
        $this->migrator->add('site.about_accreditation_2', 'BAIRA (Bangladesh Association of International Recruiting Agencies) Member');
        $this->migrator->add('site.about_accreditation_3', 'Authorized Embassy Enrolment Agency (Saudi Arabia, UAE, Qatar, Malaysia)');
        $this->migrator->add('site.about_accreditation_4', 'ISO 9001:2015 Certified Quality Management System for Overseas Recruitment');
        $this->migrator->add('site.about_office_title', 'Visit Our Corporate Office');
        $this->migrator->add('site.about_office_hours', 'Office Hours: Sat - Thu (09:00 AM - 06:00 PM)');
        $this->migrator->add('site.about_leadership_title', 'Our Leadership Team');
        $this->migrator->add('site.about_leadership_subtitle', 'Board of Directors');
    }
};
