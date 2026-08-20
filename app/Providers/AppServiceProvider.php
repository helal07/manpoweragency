<?php

namespace App\Providers;

use App\Settings\SiteSettings;
use Illuminate\Support\Facades\Gate;
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
        // Implicitly grant "super_admin" role all permissions
        Gate::before(function ($user, $ability) {
            return method_exists($user, 'hasRole') && $user->hasRole('super_admin', 'admin') ? true : null;
        });

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

                        // About Page CMS
                        'about_banner_title' => $siteSettings->about_banner_title ?? ('About ' . ($siteSettings->site_name ?? 'Global Manpower Overseas Ltd.')),
                        'about_banner_subtitle' => $siteSettings->about_banner_subtitle ?? 'Over 18 years of pioneering excellence in overseas human resource recruitment and ethical labor migration.',
                        'about_story_title' => $siteSettings->about_story_title ?? 'Who We Are',
                        'about_story_p1' => $siteSettings->about_story_p1 ?? ($siteSettings->about_teaser ?? 'Global Manpower Overseas Ltd. is a premier government-licensed overseas recruitment agency in Bangladesh (RL-1452). Specializing in sourcing, trade-testing, and deploying skilled, semi-skilled, and professional personnel across the Middle East, Southeast Asia, and Europe.'),
                        'about_story_p2' => $siteSettings->about_story_p2 ?? 'With our state-of-the-art Trade Testing Center and experienced management team, we bridge the gap between Bangladeshi talent and international corporate demand, upholding the highest standards of worker welfare, legal compliance, and operational efficiency.',
                        'about_mission_title' => $siteSettings->about_mission_title ?? 'Our Mission',
                        'about_mission_statement' => $siteSettings->about_mission_statement ?? 'To empower Bangladeshi workforce with dignified, legal overseas employment while providing top-tier labor solutions to global employers.',
                        'about_vision_title' => $siteSettings->about_vision_title ?? 'Our Vision',
                        'about_vision_statement' => $siteSettings->about_vision_statement ?? 'To be the most ethical, transparent, and preferred overseas manpower consultancy in South Asia.',
                        'about_accreditation_title' => $siteSettings->about_accreditation_title ?? 'Government Accreditation',
                        'about_accreditation_1' => $siteSettings->about_accreditation_1 ?? 'Ministry of Expatriates\' Welfare & Overseas Employment Approved',
                        'about_accreditation_2' => $siteSettings->about_accreditation_2 ?? 'BAIRA (Bangladesh Association of International Recruiting Agencies) Member',
                        'about_accreditation_3' => $siteSettings->about_accreditation_3 ?? 'Authorized Embassy Enrolment Agency (Saudi Arabia, UAE, Qatar, Malaysia)',
                        'about_accreditation_4' => $siteSettings->about_accreditation_4 ?? 'ISO 9001:2015 Certified Quality Management System for Overseas Recruitment',
                        'about_office_title' => $siteSettings->about_office_title ?? 'Visit Our Corporate Office',
                        'about_office_hours' => $siteSettings->about_office_hours ?? 'Office Hours: Sat - Thu (09:00 AM - 06:00 PM)',
                        'about_leadership_title' => $siteSettings->about_leadership_title ?? 'Our Leadership Team',
                        'about_leadership_subtitle' => $siteSettings->about_leadership_subtitle ?? 'Board of Directors',

                        // Services Page CMS & Workflow Cards
                        'services_banner_tag' => $siteSettings->services_banner_tag ?? 'Our Solutions',
                        'services_banner_title' => $siteSettings->services_banner_title ?? 'Overseas Recruitment & Mobility Services',
                        'services_banner_desc' => $siteSettings->services_banner_desc ?? 'End-to-end solutions for foreign employer companies and Bangladeshi job seekers.',
                        'services_workflow_subtitle' => $siteSettings->services_workflow_subtitle ?? 'Step-By-Step Workflow',
                        'services_workflow_title' => $siteSettings->services_workflow_title ?? 'How We Deploy Manpower',
                        'services_workflow_step1_title' => $siteSettings->services_workflow_step1_title ?? '01. Demand Receipt',
                        'services_workflow_step1_desc' => $siteSettings->services_workflow_step1_desc ?? 'Employer posts visa demand order with embassy endorsement.',
                        'services_workflow_step2_title' => $siteSettings->services_workflow_step2_title ?? '02. Screening & Test',
                        'services_workflow_step2_desc' => $siteSettings->services_workflow_step2_desc ?? 'Shortlisting and trade testing at certified technical workshops.',
                        'services_workflow_step3_title' => $siteSettings->services_workflow_step3_title ?? '03. Medical & Visa',
                        'services_workflow_step3_desc' => $siteSettings->services_workflow_step3_desc ?? 'GAMCA medical checkup, MOFA visa stamping & BMET Smart Card.',
                        'services_workflow_step4_title' => $siteSettings->services_workflow_step4_title ?? '04. Flight Departure',
                        'services_workflow_step4_desc' => $siteSettings->services_workflow_step4_desc ?? 'Pre-departure briefing, airline ticket issue & airport assistance.',
                    ];
                }
                return [];
            });

            $view->with('siteSettings', $settingsData);
        });
    }
}
