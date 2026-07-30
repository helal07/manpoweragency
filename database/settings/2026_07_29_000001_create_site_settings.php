<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.site_name', 'Global Manpower Overseas Ltd.');
        $this->migrator->add('site.site_tagline', 'Connecting Skilled Talent with Global Opportunities');
        $this->migrator->add('site.logo_path', null);
        $this->migrator->add('site.favicon_path', null);
        $this->migrator->add('site.phone', '+880 2-9876543');
        $this->migrator->add('site.email', 'info@globalmanpower.com');
        $this->migrator->add('site.address', 'House 42, Road 11, Block D, Banani, Dhaka-1213, Bangladesh');
        $this->migrator->add('site.bmet_license_no', 'RL-1452 (Ministry of Expatriates\' Welfare & Overseas Employment)');
        $this->migrator->add('site.facebook_url', 'https://facebook.com');
        $this->migrator->add('site.linkedin_url', 'https://linkedin.com');
        $this->migrator->add('site.twitter_url', 'https://twitter.com');
        $this->migrator->add('site.footer_copyright', '© 2026 Global Manpower Overseas Ltd. All Rights Reserved.');
        $this->migrator->add('site.about_teaser', 'Global Manpower Overseas Ltd. is a premier government-licensed overseas recruitment agency in Bangladesh (RL-1452). With over 18 years of industry excellence, we specialize in placing skilled, semi-skilled, and professional manpower across Saudi Arabia, UAE, Qatar, Kuwait, Malaysia, and Europe.');

        // Navigation Menu Labels
        $this->migrator->add('site.nav_home_label', 'Home');
        $this->migrator->add('site.nav_about_label', 'About');
        $this->migrator->add('site.nav_clients_label', 'Clients');
        $this->migrator->add('site.nav_services_label', 'Services');
        $this->migrator->add('site.nav_circulars_label', 'Job Circular');
        $this->migrator->add('site.nav_notices_label', 'Notice');
        $this->migrator->add('site.nav_login_label', 'Applicant Login');
    }
};
