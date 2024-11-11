<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JapaneseStudyApplication>
 */
use App\Models\JapaneseStudyApplication;
use App\Models\JapaneseStudyCourse;
use App\Models\Student;

class JapaneseStudyApplicationFactory extends Factory
{
    protected $model = JapaneseStudyApplication::class;

    public function definition()
    {
        return [
            'japanese_study_course_id' => JapaneseStudyCourse::factory(),
            'student_id' => Student::factory(),
            'month_of_use' => $this->faker->date(),
            'start_date' => $this->faker->date(),
            'estimated_end_date' => $this->faker->date(),
            'monthly_amount' => $this->faker->numberBetween(10000, 50000),
            'contract_date' => $this->faker->dateTime(),
            'credit_card_company' => $this->faker->company(),
            'credit_card_number' => $this->faker->creditCardNumber(),
            'credit_card_expiry' => $this->faker->creditCardExpirationDate(),
            'credit_card_security_number' => $this->faker->numberBetween(100, 999),
        ];
    }
}
