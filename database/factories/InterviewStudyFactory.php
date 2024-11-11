<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InterviewStudy>
 */
use App\Models\InterviewStudy;
use App\Models\Student;

class InterviewStudyFactory extends Factory
{
    protected $model = InterviewStudy::class;

    public function definition()
    {
        return [
            'student_id' => Student::factory(),
            'study_category' => $this->faker->randomElement(['Orientation', 'InterviewAnswerCreation', 'InterviewAnswerPractice', 'MockInterview']),
            'activity_date' => $this->faker->date(),
            'prompt_link' => $this->faker->url(),
            'conversation_script_link' => $this->faker->url(),
            'conversation_audio_link' => $this->faker->url(),
        ];
    }
}
