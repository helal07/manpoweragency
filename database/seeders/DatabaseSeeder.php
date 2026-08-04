<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Staff User (Filament)
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'sowayebahmedrafee@gmail.com'],
            [
                'name' => 'Sowayeb Rafee',
                'password' => Hash::make('password123'),
            ]
        );

        // Sample Applicant (Web Guard)
        Applicant::updateOrCreate(
            ['email' => 'applicant@example.com'],
            [
                'name' => 'Sample Applicant',
                'phone' => '+8801700000000',
                'nid_passport' => '1990123456789',
                'password' => Hash::make('password123'),
            ]
        );

        // Seed Site Settings & Modules
        $this->call([
            SettingSeeder::class,
            HeroBannerSeeder::class,
            JobCircularSeeder::class,
            NoticeSeeder::class,
            ServiceSeeder::class,
            ClientSeeder::class,
            LeaderSeeder::class,
            RoleSeeder::class,
            CustomFieldSeeder::class,
        ]);
    }
}
