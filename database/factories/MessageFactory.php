<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        $userTypes = ['Student', 'CompanyRepresentative', 'BusinessOperator', 'Candidate', 'CompanyAdmin'];

        return [
            'sender_user_id' => User::inRandomOrder()->first()->id,
            'sender_user_type' => $this->faker->randomElement($userTypes),
            'receiver_user_id' => User::inRandomOrder()->first()->id,
            'receiver_user_type' => $this->faker->randomElement($userTypes),
            'sent_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'message_category' => $this->faker->randomElement(['Received', 'Sent', 'Saved', 'Deleted']),
            'parent_id' => null,
            'read_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 month', 'now'),
            'inquiry_type' => $this->faker->randomElement(['general', 'interview', 'application']),
        ];
    }

    public function reply()
    {
        return $this->state(function (array $attributes) {
            $parentMessage = Message::factory()->create();
            return [
                'parent_id' => $parentMessage->id,
                'title' => 'Re: ' . $parentMessage->title,
                'sender_user_id' => $parentMessage->receiver_user_id,
                'sender_user_type' => $parentMessage->receiver_user_type,
                'receiver_user_id' => $parentMessage->sender_user_id,
                'receiver_user_type' => $parentMessage->sender_user_type,
                'inquiry_type' => $parentMessage->inquiry_type,
            ];
        });
    }

    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => null,
            ];
        });
    }
}
