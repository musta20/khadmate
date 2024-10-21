<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {            $order = Order::factory()->create(['status' => 'completed']);

        return [

            'order_id' => $order->id,
            'reviewer_id' => $order->client_id,
            'reviewee_id' => $order->freelancer_id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(),
        
        ];
    }





    public function fromClient()
    {
        return $this->state(function (array $attributes) {
            $order = Order::findOrFail($attributes['order_id']);
            return [
                'reviewer_id' => $order->client_id,
                'reviewee_id' => $order->freelancer_id,
            ];
        });
    }

    public function fromFreelancer()
    {
        return $this->state(function (array $attributes) {
            $order = Order::findOrFail($attributes['order_id']);
            return [
                'reviewer_id' => $order->freelancer_id,
                'reviewee_id' => $order->client_id,
            ];
        });
    }
}
