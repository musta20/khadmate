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
       // $images = array_map('basename', glob(storage_path('Images') . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE));

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

        // Update all freelancers with a random string avatar
        User::where('role', 'admin')->orWhere('role', 'freelancer')->update([
            'avatar' => "bddab986-757d-4676-a3f9-e6fe633191e8.jpg"//$this->copyImage($images[array_rand($images)])
        ]);
    }


    public function copyImage($image): string
    {

      //  $image = $images[array_rand($image)];
         $imagePath = storage_path("Images/{$image}");

       // [$width, $height] = getimagesize($imagePath);

        $ext = pathinfo($imagePath, PATHINFO_EXTENSION);

        $name = Str::uuid()->toString() . '.' . $ext;

        Storage::disk('avatar')->put($name, file_get_contents($imagePath));

        return $name;
        // return [
        //     'path' => $name,
        // //    'width' => $width,
        //    // 'height' => $height
        //    ];
    }
}
//  $avatar = $this->copyImage($images[array_rand($images)])['path'];