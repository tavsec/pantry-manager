<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultCategories = [
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

        // Create default categories for all existing users
        User::all()->each(function (User $user) use ($defaultCategories) {
            foreach ($defaultCategories as $category) {
                Category::firstOrCreate(
                    ['user_id' => $user->id, 'name' => $category['name']],
                    $category
                );
            }
        });
    }
}
