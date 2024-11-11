<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QualificationCategory>
 */
use App\Models\QualificationCategory;

class QualificationCategoryFactory extends Factory
{
    protected $model = QualificationCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
        ];
    }
}
