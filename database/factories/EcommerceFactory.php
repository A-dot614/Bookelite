<?php

namespace Database\Factories;
use App\Models\Ecommerce;
use App\Http\Controllers\EcommerceController;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ecommerce>
 */
class EcommerceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->text(100),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'image_url' => $this->faker->imageUrl(420, 520),
            'is_active' => $this->faker->boolean,
            'slug' => $this->faker->unique()->slug(),
        ];
    }
}
