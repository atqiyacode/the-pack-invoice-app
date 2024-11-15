<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Hash;

it('can login a user with valid credentials', function () {
    // Arrange: Create a user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    // Act: Attempt to login with valid credentials
    $response = $this->postJson(route('login'), [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    // Assert: Check response and token
    $response->assertStatus(200)
        ->assertJsonStructure(['token']);

    expect(auth()->user()->id)->toBe($user->id);
});

it('fails to login with invalid credentials', function () {
    // Arrange: Create a user
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    // Act: Attempt to login with invalid credentials
    $response = $this->postJson(route('login'), [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    // Assert: Check error response
    $response->assertStatus(422);
});

it('can logout a user', function () {
    // Arrange: Create and login a user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $token = $user->createToken('authToken')->plainTextToken;

    // Act: Attempt to logout
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson(route('logout'));

    // Assert: Check logout response
    $response->assertStatus(200)
        ->assertJson(['message' => 'Logged out successfully']);
});

it('fails to logout when unauthenticated', function () {
    // Act: Attempt to logout without a valid token
    $response = $this->postJson(route('logout'));

    // Assert: Check error response
    $response->assertStatus(401);
});
