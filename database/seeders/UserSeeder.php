<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Create 50 random users
                User::factory(50)->create();

                // Create 20 clients
                User::factory(20)->client()->create();
        
                // Create 20 freelancers
                User::factory(20)->freelancer()->create();
        
                // Create 5 admins
                User::factory(5)->admin()->create();
        
                // Create a default admin user
                User::factory()->create([
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'role' => 'admin',
                ]);
    }
}
