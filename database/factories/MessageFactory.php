<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        return [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => $this->faker->paragraph(),
            'read' => $this->faker->boolean(),
        ];
    }

    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'read' => false,
            ];
        });
    }

    public function read()
    {
        return $this->state(function (array $attributes) {
            return [
                'read' => true,
            ];
        });
    }
}
