<?php

namespace Database\Seeders;

use App\Models\PantryItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class PantryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            $categories = $user->categories;
            $locations = $user->locations;

            if ($categories->isEmpty() || $locations->isEmpty()) {
                return;
            }

            // Create some pantry items for each user
            PantryItem::factory(15)
                ->for($user)
                ->create([
                    'category_id' => $categories->random()->id,
                    'location_id' => $locations->random()->id,
                ]);

            // Create some expired items
            PantryItem::factory(3)
                ->expired()
                ->for($user)
                ->create([
                    'category_id' => $categories->random()->id,
                    'location_id' => $locations->random()->id,
                ]);

            // Create some items expiring soon
            PantryItem::factory(5)
                ->expiringSoon()
                ->for($user)
                ->create([
                    'category_id' => $categories->random()->id,
                    'location_id' => $locations->random()->id,
                ]);
        });
    }
}
