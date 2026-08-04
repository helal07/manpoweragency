<?php

namespace Database\Seeders;

use App\Models\CustomField;
use App\Models\JobCircular;
use Illuminate\Database\Seeder;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $f1 = CustomField::updateOrCreate(
            ['name' => 'hsc_roll_no'],
            [
                'label' => 'HSC / Equivalent Roll Number',
                'type' => 'number',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 1,
                'placeholder' => 'e.g. 104523',
                'help_text' => 'Enter your Higher Secondary Board roll number if applicable.',
            ]
        );

        $f2 = CustomField::updateOrCreate(
            ['name' => 'medical_report'],
            [
                'label' => 'Medical Fit Certificate / Report',
                'type' => 'file',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 2,
                'placeholder' => null,
                'help_text' => 'Upload medical test report or fitness certificate (PDF, JPG, PNG).',
            ]
        );

        $f3 = CustomField::updateOrCreate(
            ['name' => 'trade_certificate'],
            [
                'label' => 'Trade / Technical Certificate',
                'type' => 'file',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 3,
                'placeholder' => null,
                'help_text' => 'Electrical, Welding, Driving, or HVAC certification documents.',
            ]
        );

        $f4 = CustomField::updateOrCreate(
            ['name' => 'overseas_experience_details'],
            [
                'label' => 'Previous GCC / Overseas Work Details',
                'type' => 'textarea',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 4,
                'placeholder' => 'Mention company name, country, and duration of past foreign employment...',
                'help_text' => 'Provide brief details if you have worked in GCC/Europe previously.',
            ]
        );

        // Attach custom fields to sample Job Circulars
        $circulars = JobCircular::all();
        if ($circulars->isNotEmpty()) {
            foreach ($circulars as $circular) {
                // Attach medical report as mandatory, and HSC roll / trade cert as optional
                $circular->customFields()->syncWithoutDetaching([
                    $f2->id => ['is_required' => true, 'sort_order' => 1],
                    $f1->id => ['is_required' => false, 'sort_order' => 2],
                    $f3->id => ['is_required' => false, 'sort_order' => 3],
                ]);
            }
        }
    }
}
