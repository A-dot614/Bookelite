<?php

namespace Database\Factories;

use App\Models\Ecommerce;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ecommerce>
 */
class EcommerceFactory extends Factory
{
    protected $model = Ecommerce::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        $categories = ['Philosophy', 'Architecture', 'Literature', 'Fine Art', 'Science', 'Self Development'];

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 20, 150),
            'rating' => $this->faker->randomFloat(1, 4, 5),
            'category' => $this->faker->randomElement($categories),
            'genre' => 'Classical Treatise',
            'stock' => $this->faker->numberBetween(5, 50),
            'pages' => $this->faker->numberBetween(180, 800),
            'language' => 'English',
            'isbn' => $this->faker->unique()->isbn13(),
            'image_url' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800',
            'is_active' => true,
        ];
    }
}
