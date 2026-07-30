<?php

namespace Database\Seeders;

use App\Models\Leader;
use Illuminate\Database\Seeder;

class LeaderSeeder extends Seeder
{
    public function run(): void
    {
        $leaders = [
            [
                'name' => 'Alhajj Mohammad Tareq Rahman',
                'designation' => 'Chairman',
                'photo' => 'images/leaders/chairman.png',
                'bio' => 'With over 25 years of visionary leadership in human resource management and international trade, Alhajj Tareq Rahman has pioneered transparent, legal overseas recruitment in Bangladesh.',
                'order' => 1,
            ],
            [
                'name' => 'Engr. Shahriar Hossain, CIP',
                'designation' => 'Managing Director',
                'photo' => 'images/leaders/md.png',
                'bio' => 'A Commercially Important Person (CIP) awardee with an engineering background from BUET. Spearheading ethical worker welfare, international compliance, and partnerships with Fortune 500 employer groups.',
                'order' => 2,
            ],
            [
                'name' => 'Mahfuzur Rahman Khan',
                'designation' => 'Director of Overseas Recruitment',
                'photo' => 'images/leaders/director.png',
                'bio' => 'Directing overseas operations, trade testing centers, embassy visa attestations, and employer relations across the Gulf region and Southeastern Europe.',
                'order' => 3,
            ],
        ];

        foreach ($leaders as $data) {
            Leader::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
