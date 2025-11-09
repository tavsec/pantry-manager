<?php

use App\Models\PantryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

// Index/List Tests
test('pantry index page requires authentication', function () {
    $response = $this->get(route('pantry.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view pantry index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pantry.index'));

    $response->assertSuccessful();
    $response->assertViewIs('pantry.index');
});

test('users only see their own pantry items', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $userItem = PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'My Item']);
    $otherItem = PantryItem::factory()->create(['user_id' => $otherUser->id, 'name' => 'Other Item']);

    $response = $this->actingAs($user)->get(route('pantry.index'));

    $response->assertSee('My Item');
    $response->assertDontSee('Other Item');
});

// Search and Filter Tests
test('users can search pantry items by name', function () {
    $user = User::factory()->create();
    PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Apples']);
    PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Bananas']);

    $response = $this->actingAs($user)->get(route('pantry.index', ['search' => 'Apple']));

    $response->assertSee('Apples');
    $response->assertDontSee('Bananas');
});

test('users can filter pantry items by category', function () {
    $user = User::factory()->create();
    PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Rice', 'category' => 'Grains']);
    PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Milk', 'category' => 'Dairy']);

    $response = $this->actingAs($user)->get(route('pantry.index', ['category' => 'Grains']));

    $response->assertSee('Rice');
    $response->assertDontSee('Milk');
});

test('users can filter pantry items by location', function () {
    $user = User::factory()->create();
    PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Bread', 'location' => 'Pantry']);
    PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Cheese', 'location' => 'Fridge']);

    $response = $this->actingAs($user)->get(route('pantry.index', ['location' => 'Fridge']));

    $response->assertSee('Cheese');
    $response->assertDontSee('Bread');
});

test('users can sort pantry items', function () {
    $user = User::factory()->create();
    PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Zebra Item']);
    PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Apple Item']);

    $response = $this->actingAs($user)->get(route('pantry.index', ['sort_by' => 'name', 'sort_direction' => 'asc']));

    $response->assertSuccessful();
});

// Create Tests
test('create pantry item page requires authentication', function () {
    $response = $this->get(route('pantry.create'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view create form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pantry.create'));

    $response->assertSuccessful();
    $response->assertViewIs('pantry.create');
});

test('users can create a pantry item', function () {
    $user = User::factory()->create();

    $data = [
        'name' => 'Test Item',
        'category' => 'Grains',
        'quantity' => 5,
        'unit' => 'kg',
        'purchase_date' => '2025-10-25',
        'expiration_date' => '2025-12-25',
        'location' => 'Pantry',
        'notes' => 'Test notes',
    ];

    $response = $this->actingAs($user)->post(route('pantry.store'), $data);

    $response->assertRedirect(route('pantry.index'));
    assertDatabaseHas('pantry_items', [
        'user_id' => $user->id,
        'name' => 'Test Item',
        'category' => 'Grains',
        'quantity' => 5,
    ]);
});

test('pantry item creation validates required fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('pantry.store'), []);

    $response->assertSessionHasErrors(['name', 'category', 'quantity', 'unit', 'purchase_date', 'location']);
});

test('pantry item creation validates quantity is numeric', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('pantry.store'), [
        'name' => 'Test Item',
        'category' => 'Grains',
        'quantity' => 'not-a-number',
        'unit' => 'kg',
        'purchase_date' => '2025-10-25',
        'location' => 'Pantry',
    ]);

    $response->assertSessionHasErrors(['quantity']);
});

test('pantry item creation validates expiration date is after purchase date', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('pantry.store'), [
        'name' => 'Test Item',
        'category' => 'Grains',
        'quantity' => 5,
        'unit' => 'kg',
        'purchase_date' => '2025-10-25',
        'expiration_date' => '2025-10-20',
        'location' => 'Pantry',
    ]);

    $response->assertSessionHasErrors(['expiration_date']);
});

test('users can create pantry item with photo', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $data = [
        'name' => 'Test Item',
        'category' => 'Grains',
        'quantity' => 5,
        'unit' => 'kg',
        'purchase_date' => '2025-10-25',
        'location' => 'Pantry',
        'photo' => UploadedFile::fake()->image('item.jpg'),
    ];

    $response = $this->actingAs($user)->post(route('pantry.store'), $data);

    $response->assertRedirect(route('pantry.index'));

    $item = PantryItem::where('user_id', $user->id)->first();
    expect($item->photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($item->photo_path);
});

// Edit Tests
test('edit pantry item page requires authentication', function () {
    $item = PantryItem::factory()->create();

    $response = $this->get(route('pantry.edit', $item));

    $response->assertRedirect(route('login'));
});

test('users cannot edit other users pantry items', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $item = PantryItem::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('pantry.edit', $item));

    $response->assertForbidden();
});

test('users can view edit form for their own items', function () {
    $user = User::factory()->create();
    $item = PantryItem::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('pantry.edit', $item));

    $response->assertSuccessful();
    $response->assertViewIs('pantry.edit');
});

// Update Tests
test('users can update their pantry items', function () {
    $user = User::factory()->create();
    $item = PantryItem::factory()->create(['user_id' => $user->id, 'name' => 'Old Name']);

    $data = [
        'name' => 'New Name',
        'category' => 'Dairy',
        'quantity' => 10,
        'unit' => 'lbs',
        'purchase_date' => '2025-10-26',
        'location' => 'Fridge',
    ];

    $response = $this->actingAs($user)->put(route('pantry.update', $item), $data);

    $response->assertRedirect(route('pantry.index'));
    assertDatabaseHas('pantry_items', [
        'id' => $item->id,
        'name' => 'New Name',
        'category' => 'Dairy',
    ]);
});

test('users cannot update other users pantry items', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $item = PantryItem::factory()->create(['user_id' => $otherUser->id, 'name' => 'Original']);

    $response = $this->actingAs($user)->put(route('pantry.update', $item), [
        'name' => 'Hacked',
        'category' => 'Grains',
        'quantity' => 5,
        'unit' => 'kg',
        'purchase_date' => '2025-10-25',
        'location' => 'Pantry',
    ]);

    $response->assertForbidden();
    assertDatabaseHas('pantry_items', [
        'id' => $item->id,
        'name' => 'Original',
    ]);
});

// Delete Tests
test('users can delete their pantry items', function () {
    $user = User::factory()->create();
    $item = PantryItem::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('pantry.destroy', $item));

    $response->assertRedirect(route('pantry.index'));
    assertDatabaseMissing('pantry_items', ['id' => $item->id]);
});

test('users cannot delete other users pantry items', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $item = PantryItem::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->delete(route('pantry.destroy', $item));

    $response->assertForbidden();
    assertDatabaseHas('pantry_items', ['id' => $item->id]);
});

test('deleting pantry item also deletes its photo', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $item = PantryItem::factory()->create([
        'user_id' => $user->id,
        'photo_path' => 'pantry-photos/test.jpg',
    ]);

    Storage::disk('public')->put('pantry-photos/test.jpg', 'content');

    $this->actingAs($user)->delete(route('pantry.destroy', $item));

    Storage::disk('public')->assertMissing('pantry-photos/test.jpg');
});

// Model Tests
test('pantry item can check if expired', function () {
    $expiredItem = PantryItem::factory()->expired()->create();
    $freshItem = PantryItem::factory()->create(['expiration_date' => now()->addDays(30)]);

    expect($expiredItem->isExpired())->toBeTrue();
    expect($freshItem->isExpired())->toBeFalse();
});

test('pantry item can check if expiring soon', function () {
    $expiringSoonItem = PantryItem::factory()->expiringSoon()->create();
    $freshItem = PantryItem::factory()->create(['expiration_date' => now()->addDays(30)]);

    expect($expiringSoonItem->isExpiringSoon())->toBeTrue();
    expect($freshItem->isExpiringSoon())->toBeFalse();
});

test('pantry item expiration status attribute works correctly', function () {
    $expiredItem = PantryItem::factory()->expired()->create();
    $expiringSoonItem = PantryItem::factory()->expiringSoon()->create();
    $freshItem = PantryItem::factory()->create(['expiration_date' => now()->addDays(30)]);

    expect($expiredItem->expiration_status)->toBe('expired');
    expect($expiringSoonItem->expiration_status)->toBe('expiring-soon');
    expect($freshItem->expiration_status)->toBe('fresh');
});
