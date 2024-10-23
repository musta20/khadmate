<?php

namespace Database\Factories;

use App\Models\ServiceImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceImage>
 */
class ServiceImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ServiceImage::class;

    public function definition()
    {
        return [
            'image_path' => $this->faker->imageUrl(800, 600),
            'is_primary' => false,
            'order' => 0
        ];
    }

    public function primary()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_primary' => true,
                'order' => 0
            ];
        });
    }

    public function setUrl($image_path)
    {
        return $this->state(function (array $attributes) use ($image_path)  {
            return [
                'image_path' => $image_path,
                'order' => 0
            ];
        });
    }
}
