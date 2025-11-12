<?php

use App\Models\PantryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard page requires authentication', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertSuccessful();
    $response->assertViewIs('dashboard');
});

test('dashboard displays last 5 added items', function () {
    $user = User::factory()->create();

    // Create 7 pantry items with distinct timestamps
    $items = collect();
    for ($i = 0; $i < 7; $i++) {
        $item = PantryItem::factory()->create([
            'user_id' => $user->id,
            'name' => "Item {$i}",
            'created_at' => now()->subMinutes(7 - $i), // Older items first
        ]);
        $items->push($item);
    }

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertSuccessful();

    // Should see the 5 most recent items (Item 2-6)
    for ($i = 2; $i < 7; $i++) {
        $response->assertSee("Item {$i}");
    }

    // Should not see the 2 oldest items (Item 0-1)
    $response->assertDontSee('Item 0');
    $response->assertDontSee('Item 1');
});

test('dashboard handles users with no items', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertSuccessful();
    $response->assertViewIs('dashboard');
});

test('dashboard only shows current user items', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $userItem = PantryItem::factory()->create([
        'user_id' => $user->id,
        'name' => 'My Item',
    ]);

    $otherItem = PantryItem::factory()->create([
        'user_id' => $otherUser->id,
        'name' => 'Other User Item',
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertSuccessful();
    $response->assertSee('My Item');
    $response->assertDontSee('Other User Item');
});
