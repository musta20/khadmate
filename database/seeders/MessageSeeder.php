<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Get all orders
          $orders = Order::all();

          foreach ($orders as $order) {
              // Generate a random number of messages for each order (0 to 10)
              $messageCount = rand(0, 10);
  
              for ($i = 0; $i < $messageCount; $i++) {
                  // Randomly decide if the client or freelancer is sending the message
                  if (rand(0, 1) === 0) {
                      $sender_id = $order->client_id;
                      $receiver_id = $order->freelancer_id;
                  } else {
                      $sender_id = $order->freelancer_id;
                      $receiver_id = $order->client_id;
                  }
  
                  Message::factory()->create([
                      'sender_id' => $sender_id,
                      'receiver_id' => $receiver_id,
                  ]);
              }
          }
  
    }
}
