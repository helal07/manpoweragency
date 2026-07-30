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

    public static function group(): string
    {
        return 'site';
    }
}