<?php

namespace Database\Factories;

use App\Models\CompanyRepresentative;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyRepresentativeFactory extends Factory
{
    protected $model = CompanyRepresentative::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory()->state(['user_type' => 'CompanyRepresentative']),
            'company_id' => Company::factory(),
            'name_kanji' => $this->faker->name,
            'name_katakana' => $this->faker->name,
            'contact_phone_number' => $this->faker->phoneNumber,
        ];
    }
}
