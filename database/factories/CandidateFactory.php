<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Student;
use App\Models\Country;
use App\Models\Qualification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition()
    {

        return [
            'student_id' => Student::factory(),
            'publish_category' => $this->faker->randomElement(['NotPublished', 'Published', 'PublicationStopped']),
            'name' => $this->faker->name,
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'birth_date' => $this->faker->date,
            'nationality' => Country::inRandomOrder()->first(),
            'last_education' => $this->faker->randomElement(['High School', 'Bachelor', 'Master', 'PhD']),
            'work_history' => $this->faker->paragraph,
            'qualification' => Qualification::factory(),
            'self_presentation' => $this->faker->paragraph,
            'personal_preference' => $this->faker->sentence,
            // 'profile_picture_link' => $this->faker->imageUrl(),
            'self_introduction_video_link' => $this->faker->url,
            'cv_link' => $this->faker->url,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
