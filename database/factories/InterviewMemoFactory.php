<?php

namespace Database\Factories;

use App\Models\InterviewMemo;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewMemoFactory extends Factory
{
    protected $model = InterviewMemo::class;

    public function definition()
    {
        return [
            'interview_id' => Interview::factory(),
            'user_id' => User::factory(),
            'content' => $this->faker->paragraph,
        ];
    }
}
