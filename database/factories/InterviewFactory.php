<?php

namespace Database\Factories;

use App\Models\Interview;
use App\Models\Candidate;
use App\Models\Vacancy;
use App\Models\InterviewSchedule;
use App\Models\User;
use App\Enum\InterviewStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewFactory extends Factory
{
    protected $model = Interview::class;

    public function definition()
    {
        return [
            'candidate_id' => Candidate::factory(),
            'vacancy_id' => Vacancy::factory(),
            'interview_schedule_id' => InterviewSchedule::factory(),
            'implementation_date' => $this->faker->date,
            'implementation_start_time' => $this->faker->dateTime,
            'status' => $this->faker->randomElement(InterviewStatus::cases())->value,
            'reason' => $this->faker->optional()->sentence,
            'zoom_link' => $this->faker->url,
            'booking_request_date_student' => $this->faker->optional()->date,
            'booking_request_date_company' => $this->faker->optional()->date,
            'booking_confirmation_date' => $this->faker->optional()->date,
            'result_notification_date' => $this->faker->optional()->date,
            'result' => $this->faker->randomElement(['Pending', 'Passing', 'Failed', 'Cancelled', 'NotApplicable']),
            'employment_contract_procedure_application_date' => $this->faker->optional()->date,
            // 'created_by' => User::factory(),
            // 'updated_by' => User::factory(),
        ];
    }
}
