<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName . '_' . uniqid(),
            'name' => $this->faker->name(25), // Limit name to 25 characters
            'email' => $this->faker->unique()->safeEmail(50), // Limit email to 50 characters
            'email_verified_at' => now(),
            'user_type' => $this->faker->randomElement(['Student', 'CompanyRepresentative', 'BusinessOperator', 'Candidate', 'CompanyAdmin']),
            'login_permission_category' => $this->faker->randomElement(['Allowed', 'NotAllowed', 'Pending']),
            'reason_for_denial_of_login_permission_category' => $this->faker->optional()->randomElement(['IncorrectAttempts']),
            'login_permitted_category_disallowed_start_time' => $this->faker->optional()->dateTimeThisYear(),
            'password' => bcrypt('password'), // default password
            'remember_token' => Str::random(10),
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}