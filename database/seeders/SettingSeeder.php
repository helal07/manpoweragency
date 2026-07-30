<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Global Manpower Overseas Ltd.',
            'site_tagline' => 'Connecting Skilled Talent with Global Opportunities',
            'company_phone' => '+880 2-9876543',
            'company_hotline' => '+880 1711-009988',
            'company_email' => 'info@globalmanpower.com',
            'company_address' => 'House 42, Road 11, Block D, Banani, Dhaka-1213, Bangladesh',
            'bmet_license_no' => 'RL-1452 (Ministry of Expatriates\' Welfare & Overseas Employment)',
            'footer_copyright' => '© 2026 Global Manpower Overseas Ltd. All Rights Reserved.',
            'facebook_url' => 'https://facebook.com',
            'linkedin_url' => 'https://linkedin.com',
            'twitter_url' => 'https://twitter.com',
            'youtube_url' => 'https://youtube.com',
            'stat_deployed' => '15,400+',
            'stat_countries' => '12+',
            'stat_clients' => '85+',
            'stat_success' => '98.5%',
            'mission_statement' => 'To empower Bangladeshi skilled and semi-skilled manpower by matching them with dignified, legal, and rewarding employment opportunities worldwide while serving international employers with integrity.',
            'vision_statement' => 'To be the most trusted and ethically compliant overseas recruitment agency in South Asia, recognized for quality labor supply and worker welfare.',
            'about_teaser' => 'Global Manpower Overseas Ltd. is a premier government-licensed (RL-1452) overseas recruitment agency in Bangladesh. With over 18 years of industry excellence, we specialize in placing skilled, semi-skilled, and professional manpower across Saudi Arabia, UAE, Qatar, Kuwait, Malaysia, and Europe.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
