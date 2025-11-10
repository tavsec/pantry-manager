<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated users are redirected to login from root', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});

test('authenticated users are redirected to dashboard from root', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertRedirect(route('dashboard'));
});
