<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin 
        User::factory()->create([
            "name" => "Abdullah",
            "email" => "admin@watch.com",
            "password" => Hash::make("password"),
            "role" => "admin"
        ]);
        
        // Create an user
        User::factory()->create([
            "name" => "Bilal",
            "email" => "user@watch.com",
            "password" => Hash::make("password"),
            "role" => "user"
        ]);      
        
                
    }
}
