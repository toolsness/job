<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\IndustryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'name_kanji' => $this->faker->company,
            'name_katakana' => $this->faker->company,
            'industry_type_id' => IndustryType::factory(),
            'address' => $this->faker->address,
            'website' => $this->faker->url,
            'contact_email' => $this->faker->companyEmail,
            'contact_phone' => $this->faker->phoneNumber,
            // 'image' => $this->faker->imageUrl(640, 480, 'business'),
        ];
    }
}
