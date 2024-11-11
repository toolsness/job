<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyAdmin;
use App\Models\CompanyRepresentative;
use App\Models\Vacancy;
use App\Models\VRContent;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition()
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id ?? Company::factory(),
            'company_representative_id' => CompanyRepresentative::factory(),
            'company_admin_id' => CompanyAdmin::factory(),
            'publish_category' => $this->faker->randomElement(['NotPublished', 'Published', 'PublicationStopped']),
            'vr_content_company_introduction_id' => VRContent::factory(),
            'vr_content_workplace_tour_id' => VRContent::factory(),
            'job_title' => $this->faker->jobTitle,
            'monthly_salary' => $this->faker->numberBetween(200000, 500000),
            'work_location' => $this->faker->address,
            'working_hours' => $this->faker->randomElement(['9:00-17:00', '10:00-18:00', '8:00-16:00']),
            'transportation_expenses' => $this->faker->randomElement(['Fully covered', 'Partially covered', 'Not covered']),
            'overtime_pay' => $this->faker->randomElement(['Yes', 'No']),
            'salary_increase_and_bonuses' => $this->faker->sentence,
            'social_insurance' => $this->faker->randomElement(['Full coverage', 'Partial coverage']),
            'other_details' => $this->faker->paragraph,
            'japanese_language' => $this->faker->randomElement(['N1', 'N2', 'N3', 'N4', 'N5']),
            'vacancy_category_id' => $this->faker->numberBetween(1, 16),
            // 'image' => $this->faker->imageUrl(640, 480, 'business'),
        ];
    }
}
