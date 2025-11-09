<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultLocations = [
            ['name' => 'Pantry', 'icon' => '🚪'],
            ['name' => 'Fridge', 'icon' => '🧊'],
            ['name' => 'Freezer', 'icon' => '❄️'],
            ['name' => 'Cabinet', 'icon' => '🗄️'],
            ['name' => 'Counter', 'icon' => '🏠'],
        ];

        // Create default locations for all existing users
        User::all()->each(function (User $user) use ($defaultLocations) {
            foreach ($defaultLocations as $location) {
                Location::firstOrCreate(
                    ['user_id' => $user->id, 'name' => $location['name']],
                    $location
                );
            }
        });
    }
}
