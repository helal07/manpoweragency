<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Almarai Food & Beverage Company',
                'country' => 'Saudi Arabia',
                'sector' => 'FMCG & Logistics',
                'website_url' => 'https://almarai.com',
                'order' => 1,
            ],
            [
                'name' => 'Binladin Group Contracting Ltd.',
                'country' => 'Saudi Arabia',
                'sector' => 'Mega Construction & Civil Engineering',
                'website_url' => 'https://sbg.com.sa',
                'order' => 2,
            ],
            [
                'name' => 'Emaar Facilities Management LLC',
                'country' => 'United Arab Emirates',
                'sector' => 'Real Estate & Facility Management',
                'website_url' => 'https://emaar.com',
                'order' => 3,
            ],
            [
                'name' => 'Transguard Group Security & Services',
                'country' => 'United Arab Emirates',
                'sector' => 'Aviation & Security Services',
                'website_url' => 'https://transguardgroup.com',
                'order' => 4,
            ],
            [
                'name' => 'Qatar Building Company (QBC)',
                'country' => 'Qatar',
                'sector' => 'Infrastructure & Roadways',
                'website_url' => 'https://qbc.com.qa',
                'order' => 5,
            ],
            [
                'name' => 'Sunway Construction Group',
                'country' => 'Malaysia',
                'sector' => 'Commercial Construction & Energy',
                'website_url' => 'https://sunwayconstruction.com.my',
                'order' => 6,
            ],
            [
                'name' => 'Gulf Catering Company WLL',
                'country' => 'Kuwait',
                'sector' => 'Hospitality & Industrial Catering',
                'website_url' => 'https://gulfcatering.com',
                'order' => 7,
            ],
            [
                'name' => 'RomStroy Infrastructura S.R.L.',
                'country' => 'Romania',
                'sector' => 'European Highway & Rail Development',
                'website_url' => 'https://romstroy.ro',
                'order' => 8,
            ],
        ];

        foreach ($clients as $data) {
            Client::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
