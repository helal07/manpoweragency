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
        // Only load settings for front-end views and the footer, ignoring Livewire backend entirely
        View::composer([
            'site.*', 
            'layouts.*', 
            'partials.*', 
            'applicant.*', 
            'filament.footer'
        ], function ($view) {
            $settingsData = \Illuminate\Support\Facades\Cache::remember('site_settings_global_cache', 3600, function () {
                if (\Illuminate\Support\Facades\Schema::hasTable('app_settings')) {
                    $siteSettings = app(\App\Settings\SiteSettings::class);
                    return [
                        'site_name' => $siteSettings->site_name ?? 'Laravel',
                        'site_tagline' => $siteSettings->site_tagline ?? '',
                        'company_phone' => $siteSettings->phone ?? '',
                        'company_email' => $siteSettings->email ?? '',
                        'company_address' => $siteSettings->address ?? '',
                        'bmet_license_no' => $siteSettings->bmet_license_no ?? '',
                        'show_bmet_license' => $siteSettings->show_bmet_license ?? false,
                        'footer_copyright' => $siteSettings->footer_copyright ?? '',
                        'facebook_url' => $siteSettings->facebook_url ?? '',
                        'linkedin_url' => $siteSettings->linkedin_url ?? '',
                        'twitter_url' => $siteSettings->twitter_url ?? '',
                        'about_teaser' => $siteSettings->about_teaser ?? '',
                        'logo_url' => $siteSettings->logo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($siteSettings->logo_path) : null,
                        'favicon_url' => $siteSettings->favicon_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($siteSettings->favicon_path) : null,

                        'nav_home_label' => $siteSettings->nav_home_label ?? 'Home',
                        'nav_about_label' => $siteSettings->nav_about_label ?? 'About',
                        'nav_clients_label' => $siteSettings->nav_clients_label ?? 'Clients',
                        'nav_services_label' => $siteSettings->nav_services_label ?? 'Services',
                        'nav_circulars_label' => $siteSettings->nav_circulars_label ?? 'Job Circulars',
                        'nav_notices_label' => $siteSettings->nav_notices_label ?? 'Notices',
                        'nav_login_label' => $siteSettings->nav_login_label ?? 'Login',
                    ];
                }
                return [];
            });

            $view->with('siteSettings', $settingsData);
        });
    }
}
