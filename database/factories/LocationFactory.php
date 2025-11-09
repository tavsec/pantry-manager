<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = [
            ['name' => 'Pantry', 'icon' => '🚪'],
            ['name' => 'Fridge', 'icon' => '🧊'],
            ['name' => 'Freezer', 'icon' => '❄️'],
            ['name' => 'Cabinet', 'icon' => '🗄️'],
            ['name' => 'Counter', 'icon' => '🏠'],
            ['name' => 'Basement', 'icon' => '🏚️'],
            ['name' => 'Garage', 'icon' => '🚗'],
        ];

        $location = fake()->randomElement($locations);

        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $location['name'],
            'icon' => $location['icon'],
        ];
    }
}
