<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Account;

class LandingPageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account_uuid' => Account::factory(),

            'name' => $this->faker->sentence(2),
            'slug' => Str::slug($this->faker->sentence(2)),
            'type' => 'infoproduct',

            'pages' => [
                'main' => [],
                'thanks' => [], 
            ],
            'draft' => [
                'main' => [],
                'thanks' => [],
            ],
        ];
    }
    
}
