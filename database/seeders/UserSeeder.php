<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = array_map('basename', glob(storage_path('Images') . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE));

                // Create 50 random users
                User::factory(50)->create();

                // Create 20 clients
                User::factory(20)->client()->create();
        
                // Create 20 freelancers
                User::factory(20)
           
                ->create([ 
                    'role' => 'freelancer',
                    'avatar' => $this->copyImage($images[array_rand($images)])['path']
                ]);
        
                // Create 5 admins
                User::factory(5)->admin()->create();
        
                // Create a default admin user
                User::factory()->create([
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'role' => 'admin',
                ]);
    }


    public function copyImage($image): array
    {

      //  $image = $images[array_rand($image)];
         $imagePath = storage_path("Images/{$image}");

       // [$width, $height] = getimagesize($imagePath);

        $ext = pathinfo($imagePath, PATHINFO_EXTENSION);

        $name = Str::uuid()->toString() . '.' . $ext;

        Storage::disk('avatar')->put($name, file_get_contents($imagePath));

        return [
            'path' => $name,
        //    'width' => $width,
           // 'height' => $height
           ];
    }
}
