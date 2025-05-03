<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
          
        ];
    }

    public function admin(): Factory
    {
        return $this->state([
            'name' => "admin",
            'email' => "admin@gmail.com",
            'password' => "admin",
            'biografia' => "Yo soy un admin",
            'role' => "admin",
            'is_banned' => false,
            "created_at" => now(),
            "updated_at" => now(),
            
        ]);
    }

}
