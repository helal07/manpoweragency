<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public string $site_name;
    public ?string $site_tagline;
    public ?string $logo_path;
    public ?string $favicon_path;
    public ?string $phone;
    public ?string $email;
    public ?string $address;
    public ?string $bmet_license_no;
    public bool $show_bmet_license;
    public ?string $facebook_url;
    public ?string $linkedin_url;
    public ?string $twitter_url;
    public ?string $footer_copyright;
    public ?string $about_teaser;

    // Custom Dynamic Navigation Menu Labels
    public ?string $nav_home_label;
    public ?string $nav_about_label;
    public ?string $nav_clients_label;
    public ?string $nav_services_label;
    public ?string $nav_circulars_label;
    public ?string $nav_notices_label;
    public ?string $nav_login_label;

    // About Page CMS Properties
    public ?string $about_banner_title;
    public ?string $about_banner_subtitle;
    public ?string $about_story_title;
    public ?string $about_story_p1;
    public ?string $about_story_p2;
    public ?string $about_mission_title;
    public ?string $about_mission_statement;
    public ?string $about_vision_title;
    public ?string $about_vision_statement;
    public ?string $about_accreditation_title;
    public ?string $about_accreditation_1;
    public ?string $about_accreditation_2;
    public ?string $about_accreditation_3;
    public ?string $about_accreditation_4;
    public ?string $about_office_title;
    public ?string $about_office_hours;
    public ?string $about_leadership_title;
    public ?string $about_leadership_subtitle;

    // Services Page CMS & Deployment Workflow Properties
    public ?string $services_banner_tag;
    public ?string $services_banner_title;
    public ?string $services_banner_desc;
    public ?string $services_workflow_subtitle;
    public ?string $services_workflow_title;
    public ?string $services_workflow_step1_title;
    public ?string $services_workflow_step1_desc;
    public ?string $services_workflow_step2_title;
    public ?string $services_workflow_step2_desc;
    public ?string $services_workflow_step3_title;
    public ?string $services_workflow_step3_desc;
    public ?string $services_workflow_step4_title;
    public ?string $services_workflow_step4_desc;

    public static function group(): string
    {
        return 'site';
    }
}