<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            ['name' => 'Grains', 'icon' => '🌾', 'color' => '#F59E0B'],
            ['name' => 'Dairy', 'icon' => '🥛', 'color' => '#EFF6FF'],
            ['name' => 'Vegetables', 'icon' => '🥕', 'color' => '#10B981'],
            ['name' => 'Fruits', 'icon' => '🍎', 'color' => '#EF4444'],
            ['name' => 'Meat', 'icon' => '🍖', 'color' => '#DC2626'],
            ['name' => 'Canned Goods', 'icon' => '🥫', 'color' => '#6B7280'],
            ['name' => 'Snacks', 'icon' => '🍿', 'color' => '#FBBF24'],
            ['name' => 'Beverages', 'icon' => '🥤', 'color' => '#3B82F6'],
            ['name' => 'Condiments', 'icon' => '🧂', 'color' => '#8B5CF6'],
            ['name' => 'Frozen', 'icon' => '🧊', 'color' => '#06B6D4'],
        ];

        $category = fake()->randomElement($categories);

        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $category['name'],
            'icon' => $category['icon'],
            'color' => $category['color'],
        ];
    }
}
