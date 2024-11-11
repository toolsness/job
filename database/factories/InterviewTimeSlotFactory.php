<?php

namespace Database\Factories;

use App\Models\InterviewTimeSlot;
use App\Models\Company;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class InterviewTimeSlotFactory extends Factory
{
    protected $model = InterviewTimeSlot::class;

    public function definition()
    {
        $startTime = Carbon::instance($this->faker->dateTimeBetween('09:00:00', '16:00:00'));
        $endTime = $startTime->copy()->addHour();

        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'vacancy_id' => $this->faker->boolean(51) ? null : Vacancy::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'status' => $this->faker->randomElement(['available', 'booked', 'completed', 'cancelled']),
        ];
    }

    public function forVacancy(Vacancy $vacancy)
    {
        return $this->state(function (array $attributes) use ($vacancy) {
            return [
                'vacancy_id' => $vacancy->id,
            ];
        });
    }

    public function withoutVacancy()
    {
        return $this->state(function (array $attributes) {
            return [
                'vacancy_id' => null,
            ];
        });
    }
}
