<?php
namespace Database\Seeders;

use App\Http\Controllers\EcommerceController;
use App\Models\Ecommerce;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ecommerce::factory(14)->create();

        Ecommerce::factory()->create([
            'title' => 'Test Ecommerce',
            'description' => 'Test Description',
            'price' => 29.99,
            'rating' => 4.5,
            'image_url' => 'https://picsum.photos/400/400',
            'is_active' => true,
            'slug' => 'test-ecommerce',
        ]);
        Ecommerce::factory(14)->create();



    }
}
