<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory()->state(['user_type' => 'Student']),
            'name_kanji' => $this->faker->name,
            'name_katakana' => $this->faker->name,
            'name_japanese' => $this->faker->name,
            'contact_phone_number' => $this->faker->phoneNumber,
        ];
    }
}
