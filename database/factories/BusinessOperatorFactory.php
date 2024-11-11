<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessOperator>
 */
use App\Models\BusinessOperator;
use App\Models\User;

class BusinessOperatorFactory extends Factory
{
    protected $model = BusinessOperator::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->state(['user_type' => 'BusinessOperator']),
            'name_kanji' => $this->faker->name,
            'name_katakana' => $this->faker->name,
            'contact_phone_number' => $this->faker->phoneNumber,
        ];
    }
}
