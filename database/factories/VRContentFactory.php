<?php

namespace Database\Factories;

use App\Models\VRContent;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class VRContentFactory extends Factory
{
    protected $model = VRContent::class;

    public function definition()
    {
        return [
            'content_name' => $this->faker->words(3, true),
            'company_id' => Company::inRandomOrder()->first()->id ?? Company::factory(),
            'content_category' => $this->faker->randomElement(['CompanyIntroduction', 'WorkplaceTour']),
            'content_link' => $this->faker->url,
            // 'image' => $this->faker->imageUrl(640, 480, 'business'),
            'remarks' => $this->faker->optional()->sentence,
            'status' => $this->faker->randomElement(['Public', 'Private', 'Draft']),
        ];
    }
}
