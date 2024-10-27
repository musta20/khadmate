<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $completedOrders = Order::where('status', 'completed')->get();

        foreach ($completedOrders as $order) {
            // 80% chance of client leaving a review
            if (rand(1, 100) <= 90) {
                Review::factory()->fromClient()->create(['order_id' => $order->id]);
            }

            // 60% chance of freelancer leaving a review
            // if (rand(1, 100) <= 90) {
            //     Review::factory()->fromFreelancer()->create(['order_id' => $order->id]);
            // }
        }
    }
}
