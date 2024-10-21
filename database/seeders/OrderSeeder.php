<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
             // Get all services
             $services = Service::all();

             // Get all client users
             $clients = User::where('role', 'client')->get();
     
             // Create orders
             foreach ($services as $service) {
     
                 Order::factory()
                     ->count(3)
                     ->create([
                         'service_id' => $service->id,
                         'client_id' => $clients->random()->id,
                         'freelancer_id' => $service->user_id,
                     ]);
             }
     
    }
}
