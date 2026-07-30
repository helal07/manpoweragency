<?php

namespace Database\Seeders;

use App\Models\Notice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        $notices = [
            [
                'title' => 'Delegation Walk-in Interview: Security Guards for UAE Project',
                'slug' => Str::slug('Delegation Walk-in Interview Security Guards for UAE Project'),
                'category' => 'Interview Schedule',
                'description' => 'Foreign employer delegation from Dubai will conduct direct face-to-face interviews and physical fitness evaluations on August 5th, 2026 at our Banani Training Head Office. All shortlisted candidates must carry original passport, 12 passport pictures, and educational certificates.',
                'is_pinned' => true,
                'published_at' => now()->subDays(1)->format('Y-m-d'),
            ],
            [
                'title' => 'Flight Departure Announcement: Batch #142 (Saudi Arabia Workers)',
                'slug' => Str::slug('Flight Departure Announcement Batch 142 Saudi Arabia Workers'),
                'category' => 'Flight Schedule',
                'description' => 'The flight for 45 selected workers under KSA Visa Demand Order #9910 is confirmed for August 10th on Saudia Airlines Flight SV-805 departing from Dhaka Airport (DAC) at 21:40. Candidates are advised to report to our Airport briefing desk 4 hours prior.',
                'is_pinned' => true,
                'published_at' => now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'title' => 'Updated Medical Test & GAMCA Examination Protocols',
                'slug' => Str::slug('Updated Medical Test GAMCA Examination Protocols'),
                'category' => 'Medical Test',
                'description' => 'All candidates registered for Gulf countries (KSA, UAE, Qatar, Kuwait) must complete their medical checkups only through authorized GAMCA Medical Centers. Please check your assigned medical clinic code on the candidate portal.',
                'is_pinned' => false,
                'published_at' => now()->subDays(4)->format('Y-m-d'),
            ],
            [
                'title' => 'Pre-Departure Orientation Training (PDO) Schedule',
                'slug' => Str::slug('Pre-Departure Orientation Training PDO Schedule'),
                'category' => 'BMET Clearance',
                'description' => 'Mandatory 3-day BMET Pre-Departure Orientation Class for Malaysia and Romania candidates will start on August 3rd at 09:00 AM in our TTC Auditorium.',
                'is_pinned' => false,
                'published_at' => now()->subDays(7)->format('Y-m-d'),
            ],
            [
                'title' => 'Beware of Unauthorized Brokers & Illegal Cash Transactions',
                'slug' => Str::slug('Beware of Unauthorized Brokers Illegal Cash Transactions'),
                'category' => 'General',
                'description' => 'Global Manpower Overseas Ltd. strictly collects fees through official bank accounts or office counter receipts with authorized seal. Never pay money to unauthorized field agents or middlemen.',
                'is_pinned' => false,
                'published_at' => now()->subDays(10)->format('Y-m-d'),
            ],
        ];

        foreach ($notices as $data) {
            Notice::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
