<?php

namespace App\Providers;

use App\Settings\SiteSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $settingsData = [];

            if (Schema::hasTable('app_settings')) {
                try {
                    $siteSettings = app(SiteSettings::class);
                    $settingsData = [
                        'site_name' => $siteSettings->site_name ?? 'Global Manpower Overseas Ltd.',
                        'site_tagline' => $siteSettings->site_tagline ?? 'Connecting Skilled Talent with Global Opportunities',
                        'company_phone' => $siteSettings->phone ?? '+880 2-9876543',
                        'company_email' => $siteSettings->email ?? 'info@globalmanpower.com',
                        'company_address' => $siteSettings->address ?? 'House 42, Road 11, Block D, Banani, Dhaka-1213, Bangladesh',
                        'bmet_license_no' => $siteSettings->bmet_license_no ?? 'RL-1452 (Ministry of Expatriates\' Welfare & Overseas Employment)',
                        'show_bmet_license' => $siteSettings->show_bmet_license ?? true,
                        'footer_copyright' => $siteSettings->footer_copyright ?? '© 2026 Global Manpower Overseas Ltd. All Rights Reserved.',
                        'facebook_url' => $siteSettings->facebook_url ?? 'https://facebook.com',
                        'linkedin_url' => $siteSettings->linkedin_url ?? 'https://linkedin.com',
                        'twitter_url' => $siteSettings->twitter_url ?? 'https://twitter.com',
                        'about_teaser' => $siteSettings->about_teaser ?? 'Global Manpower Overseas Ltd. is a premier government-licensed overseas recruitment agency in Bangladesh (RL-1452).',
                        'logo_url' => $siteSettings->logo_path ? asset('storage/' . $siteSettings->logo_path) : null,
                        'favicon_url' => $siteSettings->favicon_path ? asset('storage/' . $siteSettings->favicon_path) : null,

                        'nav_home_label' => $siteSettings->nav_home_label ?? 'Home',
                        'nav_about_label' => $siteSettings->nav_about_label ?? 'About',
                        'nav_clients_label' => $siteSettings->nav_clients_label ?? 'Clients',
                        'nav_services_label' => $siteSettings->nav_services_label ?? 'Services',
                        'nav_circulars_label' => $siteSettings->nav_circulars_label ?? 'Job Circular',
                        'nav_notices_label' => $siteSettings->nav_notices_label ?? 'Notice',
                        'nav_login_label' => $siteSettings->nav_login_label ?? 'Applicant Login',
                    ];
                } catch (\Throwable $e) {
                    // Fallback default values
                    $settingsData = [
                        'site_name' => 'Global Manpower Overseas Ltd.',
                        'site_tagline' => 'Connecting Skilled Talent with Global Opportunities',
                        'company_phone' => '+880 2-9876543',
                        'company_email' => 'info@globalmanpower.com',
                        'company_address' => 'House 42, Road 11, Block D, Banani, Dhaka-1213, Bangladesh',
                        'bmet_license_no' => 'RL-1452 (Ministry of Expatriates\' Welfare & Overseas Employment)',
                        'show_bmet_license' => true,
                        'footer_copyright' => '© 2026 Global Manpower Overseas Ltd. All Rights Reserved.',

                        'nav_home_label' => 'Home',
                        'nav_about_label' => 'About',
                        'nav_clients_label' => 'Clients',
                        'nav_services_label' => 'Services',
                        'nav_circulars_label' => 'Job Circular',
                        'nav_notices_label' => 'Notice',
                        'nav_login_label' => 'Applicant Login',
                    ];
                }
            }

            $view->with('siteSettings', $settingsData);
        });
    }
}
