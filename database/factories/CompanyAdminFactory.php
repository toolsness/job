<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyAdmin>
 */

use App\Models\CompanyAdmin;
use App\Models\User;
use App\Models\Company;

class CompanyAdminFactory extends Factory
{
    protected $model = CompanyAdmin::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory()->state(['user_type' => 'CompanyAdmin']),
            'company_id' => Company::factory(),
            'name_kanji' => $this->faker->name,
            'name_katakana' => $this->faker->name,
            'contact_phone_number' => $this->faker->phoneNumber,
        ];
    }
}
