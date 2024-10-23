<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceImage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $images = array_map('basename', glob(storage_path('Images') . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE));

     //   dd($images);
        // Get all image names from the /storage/Images folder
        //$imageNames = $this->getImageNamesFromStorage();

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
                ])
                ->each(function ($service) use ($images) {
                     $data = $this->copyImage($images[array_rand($images)]);

                    // Create primary image
                    ServiceImage::factory()
                        ->create([
                            'service_id' => $service->id,
                            'is_primary' => true,
                            'image_path' =>  $data['path']//$imageNames ? $imageNames[array_rand($imageNames)] : null,
                        ]);

                    // Create additional images
                    ServiceImage::factory()
                        ->count(rand(2, 5))
                        ->sequence(fn ($sequence) => ['order' => $sequence->index + 1])
                        ->create([
                            'service_id' => $service->id,
                            'image_path' => $data['path'] // ? $imageNames[array_rand($imageNames)] : null,
                        ]);
                });
        }
    }

    /**
     * Get all image names from the /storage/Images folder.
     *
     * @return array
     */
    private function getImageNamesFromStorage(): array
    {
        $imageNames = [];
        $files = Storage::disk('public')->files('Images');
 
        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                $imageNames[] = $file;
            }
        }
         return $imageNames;
    }
    public function copyImage($image): array
    {

      //  $image = $images[array_rand($image)];
         $imagePath = storage_path("Images/{$image}");

       // [$width, $height] = getimagesize($imagePath);

        $ext = pathinfo($imagePath, PATHINFO_EXTENSION);

        $name = Str::uuid()->toString() . '.' . $ext;

        Storage::disk('service')->put($name, file_get_contents($imagePath));

        return [
            'path' => $name,
        //    'width' => $width,
           // 'height' => $height
           ];
    }
}
