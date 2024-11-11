<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IndustryType;

class IndustryTypeFactory extends Factory
{
    protected $model = IndustryType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
        ];
    }
}
