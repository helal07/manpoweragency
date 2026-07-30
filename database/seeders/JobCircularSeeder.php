<?php

namespace Database\Seeders;

use App\Models\JobCircular;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobCircularSeeder extends Seeder
{
    public function run(): void
    {
        $circulars = [
            [
                'title' => 'Security Guard (Residential & Commercial)',
                'slug' => Str::slug('Security Guard Residential Commercial UAE'),
                'country' => 'United Arab Emirates',
                'category' => 'Security Services',
                'vacancy' => 50,
                'salary_range' => '1,800 - 2,200 AED / month',
                'deadline' => now()->addDays(25)->format('Y-m-d'),
                'status' => 'open',
                'description' => 'Urgent requirement for licensed security officers for top commercial towers and luxury residential complexes in Dubai. Free accommodation, transport, and medical insurance provided by employer.',
                'requirements' => '• Minimum height 5ft 8in.
• Secondary school certificate or equivalent.
• Basic English communication skills.
• Prior military, police, or security background preferred.',
                'posted_at' => now()->subDays(2),
            ],
            [
                'title' => 'Heavy Equipment Operator (Excavator & Crane)',
                'slug' => Str::slug('Heavy Equipment Operator Excavator Crane KSA'),
                'country' => 'Saudi Arabia',
                'category' => 'Heavy Equipment & Machinery',
                'vacancy' => 30,
                'salary_range' => '2,500 - 3,200 SAR / month',
                'deadline' => now()->addDays(18)->format('Y-m-d'),
                'status' => 'open',
                'description' => 'Leading infrastructure project in Riyadh requires certified excavator and mobile crane operators. Overtime payment available as per KSA labor law.',
                'requirements' => '• Valid Bangladeshi heavy driving license or Gulf license.
• Minimum 3 years experience operating CAT/Komatsu machinery.
• Pass trade test at BMET authorized technical center.',
                'posted_at' => now()->subDays(4),
            ],
            [
                'title' => 'General Construction Worker & Mason',
                'slug' => Str::slug('General Construction Worker Mason Qatar'),
                'country' => 'Qatar',
                'category' => 'Construction & Civil',
                'vacancy' => 100,
                'salary_range' => '1,400 - 1,800 QAR / month',
                'deadline' => now()->addDays(30)->format('Y-m-d'),
                'status' => 'open',
                'description' => 'Recruitment for major urban expansion project in Lusail, Qatar. Food allowance, accommodation, and annual flight ticket included.',
                'requirements' => '• Physical fitness certificate required.
• Age between 21 to 38 years.
• Basic construction site safety knowledge.',
                'posted_at' => now()->subDays(1),
            ],
            [
                'title' => 'Hotel Housekeeping & Catering Associate',
                'slug' => Str::slug('Hotel Housekeeping Catering Associate Malaysia'),
                'country' => 'Malaysia',
                'category' => 'Hospitality & Services',
                'vacancy' => 40,
                'salary_range' => '1,800 - 2,200 MYR / month',
                'deadline' => now()->addDays(20)->format('Y-m-d'),
                'status' => 'open',
                'description' => '5-Star Resort chain in Kuala Lumpur & Penang seeking polite, energetic housekeeping staff and kitchen helpers.',
                'requirements' => '• Age 20 - 32 years.
• Good personal hygiene and energetic personality.
• Basic conversational English.',
                'posted_at' => now()->subDays(5),
            ],
            [
                'title' => 'Industrial Assembly & Packaging Worker',
                'slug' => Str::slug('Industrial Assembly Packaging Worker Romania'),
                'country' => 'Romania',
                'category' => 'Manufacturing & Factory',
                'vacancy' => 25,
                'salary_range' => '3,200 - 3,800 RON / month',
                'deadline' => now()->addDays(40)->format('Y-m-d'),
                'status' => 'open',
                'description' => 'European Union work permit visa for electronics manufacturing plant in Bucharest, Romania. 2-year renewable contract with pathway to residence permit.',
                'requirements' => '• High school diploma.
• Good hand-eye coordination.
• Clean police clearance certificate.',
                'posted_at' => now()->subDays(3),
            ],
            [
                'title' => 'Certified Industrial Electrician & Pipe Fitter',
                'slug' => Str::slug('Certified Industrial Electrician Pipe Fitter Kuwait'),
                'country' => 'Kuwait',
                'category' => 'Technical & MEP',
                'vacancy' => 20,
                'salary_range' => '200 - 260 KWD / month',
                'deadline' => now()->addDays(15)->format('Y-m-d'),
                'status' => 'open',
                'description' => 'Maintenance contract for refinery complex in Mina Al-Ahmadi. High salary with safety hazard allowance and overtime bonuses.',
                'requirements' => '• Diploma in Electrical or Mechanical Trade (VTI/Polytechnic).
• Minimum 2 years plant maintenance experience.
• Ability to read single-line diagrams & blueprints.',
                'posted_at' => now()->subDays(6),
            ],
        ];

        foreach ($circulars as $data) {
            JobCircular::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
