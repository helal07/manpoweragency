<?php

namespace Database\Seeders;

use App\Models\HeroBanner;
use Illuminate\Database\Seeder;

class HeroBannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Connecting Skilled Talent with Overseas Opportunities',
                'subtitle' => 'Providing world-class manpower solutions across Middle East, Southeast Asia, and Europe.',
                'cta_label' => 'Browse Job Circulars',
                'cta_url' => '/job-circulars',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Government Licensed Overseas Recruitment Agency',
                'subtitle' => 'BMET Approved License RL-1452 ensuring 100% legal, transparent, and ethical worker deployment.',
                'cta_label' => 'Learn About Agency',
                'cta_url' => '/about',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'State-of-the-Art Trade Testing & Skill Assessment',
                'subtitle' => 'Verifying practical technical competence before international candidate placement.',
                'cta_label' => 'Explore Our Services',
                'cta_url' => '/services',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            HeroBanner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
