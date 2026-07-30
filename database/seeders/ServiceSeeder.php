<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Overseas Manpower Sourcing & Recruitment',
                'slug' => Str::slug('Overseas Manpower Sourcing Recruitment'),
                'icon' => 'user-group',
                'short_description' => 'Sourcing highly qualified skilled, semi-skilled, and professional personnel matched to exact international employer specifications.',
                'full_description' => 'Our extensive database of over 100,000 registered workers across Bangladesh allows us to rapidly recruit qualified personnel for construction, security, healthcare, hospitality, engineering, and manufacturing sectors. We perform rigorous background checks and multi-stage screening.',
                'order' => 1,
            ],
            [
                'title' => 'Trade Testing & Skill Assessment Center',
                'slug' => Str::slug('Trade Testing Skill Assessment Center'),
                'icon' => 'academic-cap',
                'short_description' => 'Government-approved state-of-the-art trade testing center for welders, electricians, heavy drivers, masons, and technicians.',
                'full_description' => 'Equipped with modern workshops and certified master trainers, our trade testing facility verifies practical competence, blueprint reading skills, and safety awareness before candidate selection.',
                'order' => 2,
            ],
            [
                'title' => 'Embassy Visa Processing & Documentation',
                'slug' => Str::slug('Embassy Visa Processing Documentation'),
                'icon' => 'document-text',
                'short_description' => 'Complete assistance with visa endorsement, attestation, medical clearance, and Ministry approvals.',
                'full_description' => 'We handle complex diplomatic documentation, embassy appointments, MOFA attestations, police clearance verifications, and visa stamping for Saudi Arabia, UAE, Qatar, Oman, Malaysia, and European countries.',
                'order' => 3,
            ],
            [
                'title' => 'BMET Smart Card & Departure Clearance',
                'slug' => Str::slug('BMET Smart Card Departure Clearance'),
                'icon' => 'identification',
                'short_description' => 'Streamlined BMET registration, immigration clearance, and insurance policy issuance for legal overseas employment.',
                'full_description' => 'Ensuring 100% legal compliance under the Ministry of Expatriates Welfare and Overseas Employment. We process government Smart Cards, welfare fund registration, and immigration clearance.',
                'order' => 4,
            ],
            [
                'title' => 'Pre-Departure Orientation & Language Training',
                'slug' => Str::slug('Pre-Departure Orientation Language Training'),
                'icon' => 'light-bulb',
                'short_description' => 'Preparing workers with destination country cultural orientation, basic language skills, and workplace safety rules.',
                'full_description' => 'Comprehensive training modules covering Arabic & English conversational phrases, destination country labor laws, workplace ethics, personal hygiene, and safety protocols to ensure smooth integration abroad.',
                'order' => 5,
            ],
            [
                'title' => 'Air Ticketing & Overseas Logistics Support',
                'slug' => Str::slug('Air Ticketing Overseas Logistics Support'),
                'icon' => 'paper-airplane',
                'short_description' => 'Group flight bookings, airport reception, and employer logistics coordination upon arrival in destination countries.',
                'full_description' => 'Dedicated flight desk managing group flight reservations, airport assistance at Dhaka Airport, and coordination with foreign employer reception teams upon arrival at foreign airports.',
                'order' => 6,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
