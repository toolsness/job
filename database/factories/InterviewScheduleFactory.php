<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InterviewSchedule>
 */
use App\Models\InterviewSchedule;
use App\Models\Vacancy;

class InterviewScheduleFactory extends Factory
{
    protected $model = InterviewSchedule::class;

    public function definition()
    {
        return [
            'vacancy_id' => Vacancy::factory(),
            'interview_date' => $this->faker->date,
            'interview_start_time' => $this->faker->dateTime,
            'reservation_status' => $this->faker->randomElement(['Pending', 'Complete', 'Reserved']),
        ];
    }
}
