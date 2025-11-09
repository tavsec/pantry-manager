<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PantryItem>
 */
class PantryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $units = ['pieces', 'kg', 'lbs', 'liters', 'ml', 'oz', 'grams', 'cans', 'boxes'];

        $purchaseDate = fake()->dateTimeBetween('-2 months', 'now');
        $daysUntilExpiration = fake()->numberBetween(1, 90);

        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->randomElement(['Rice', 'Milk', 'Carrots', 'Chicken Breast', 'Tomato Sauce', 'Chips', 'Orange Juice', 'Ketchup', 'Ice Cream']),
            'category_id' => \App\Models\Category::factory(),
            'quantity' => fake()->randomFloat(2, 0.5, 20),
            'unit' => fake()->randomElement($units),
            'purchase_date' => $purchaseDate,
            'expiration_date' => fake()->optional(0.8)->dateTimeBetween($purchaseDate, "+{$daysUntilExpiration} days"),
            'location_id' => \App\Models\Location::factory(),
            'notes' => fake()->optional(0.5)->sentence(),
            'photo_path' => null,
        ];
    }

    /**
     * Indicate that the item is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiration_date' => fake()->dateTimeBetween('-30 days', '-1 day'),
        ]);
    }

    /**
     * Indicate that the item is expiring soon.
     */
    public function expiringSoon(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiration_date' => fake()->dateTimeBetween('now', '+7 days'),
        ]);
    }
}
