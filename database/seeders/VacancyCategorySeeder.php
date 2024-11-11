<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VacancyCategory;

class VacancyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $industryTypes = [
            'Food',
            'Health',
            'Hotel',
            'IT',
            'Finance',
            'Education',
            'Agriculture',
            'Real Estate',
            'Travel',
            'Entertainment',
            'Services',
            'Hospitality',
            'Legal',
            'Construction',
            'Manufacturing',
            'Other',
        ];

        foreach ($industryTypes as $industryType) {
            VacancyCategory::create([
                'name' => $industryType,
            ]);
        }
    }
}
