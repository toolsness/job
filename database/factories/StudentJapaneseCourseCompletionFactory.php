<?php

namespace Database\Factories;

use App\Models\StudentJapaneseCourseCompletion;
use App\Models\Student;
use App\Models\JapaneseStudyCourse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentJapaneseCourseCompletionFactory extends Factory
{
    protected $model = StudentJapaneseCourseCompletion::class;

    public function definition()
    {
        return [
            'student_id' => Student::factory(),
            'japanese_study_course_id' => JapaneseStudyCourse::factory(),
            'completed_at' => $this->faker->dateTimeThisYear,
            // 'created_by' => User::factory(),
            // 'updated_by' => User::factory(),
        ];
    }
}
