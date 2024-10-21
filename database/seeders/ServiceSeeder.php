<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           // Get all freelancer users
           $freelancers = User::where('role', 'freelancer')->get();

           // Get all categories
           $categories = Category::all();
   
           // Create 5 services for each freelancer
           foreach ($freelancers as $freelancer) {
               Service::factory()
                   ->count(5)
                   ->create([
                       'user_id' => $freelancer->id,
                       'category_id' => $categories->random()->id,
                   ]);
           }
   
    }
}
