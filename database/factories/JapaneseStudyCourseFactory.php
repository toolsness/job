<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JapaneseStudyCourse>
 */
use App\Models\JapaneseStudyCourse;

class JapaneseStudyCourseFactory extends Factory
{
    protected $model = JapaneseStudyCourse::class;

    public function definition()
    {
        return [
            'course_name' => $this->faker->words(3, true),
            'course_category' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'publish_category' => $this->faker->randomElement(['NotPublished', 'Published', 'PublicationStopped']),
            'monthly_amount' => $this->faker->numberBetween(10000, 50000),
        ];
    }
}
