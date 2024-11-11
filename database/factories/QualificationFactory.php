<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Qualification>
 */
use App\Models\Qualification;
use App\Models\QualificationCategory;

class QualificationFactory extends Factory
{
    protected $model = Qualification::class;

    public function definition()
    {
        return [
            'qualification_category_id' => QualificationCategory::factory(),
            'qualification_name' => $this->faker->words(3, true),
        ];
    }
}
