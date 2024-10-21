<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
     
            $service = Service::factory()->create();
            $client = User::factory()->client()->create();
    
            return [
                'service_id' => $service->id,
                'client_id' => $client->id,
                'freelancer_id' => $service->user_id,
                'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
                'amount' => $this->faker->randomFloat(2, $service->price, $service->price * 1.5),
                'due_date' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            ];
        
    }


    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'in_progress',
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }
}
