<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $images = [
            '/images/ipad.jpeg',
            '/images/iphone.jpg',
            '/images/mac.jpeg',
        ];
        return [
            'name' => $this->faker->text(20),
            'description' => $this->faker->text,
            'image' => $images[rand(0, 2)],
        ];
    }
}
