<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list users', function () {
    User::factory()->count(2)->create();

    $response = $this->getJson('/api/users');

    $response->assertOk()
        ->assertJsonCount(2);
});

it('can create a user', function () {
    $payload = [
        'name' => 'Test user',
        'email' => 'test@example.com',
        'password' => 'password',
    ];

    $response = $this->postJson('/api/users', $payload);

    $response->assertCreated()
        ->assertJsonFragment(['name' => 'Test user', 'email' => 'test@example.com']);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

it('validates input when creating', function () {
    $response = $this->postJson('/api/users', []);

    $response->assertStatus(422);
});

it('can show a user', function () {
    $user = User::factory()->create();

    $response = $this->getJson("/api/users/{$user->id}");

    $response->assertOk()
        ->assertJsonFragment(['id' => $user->id]);
});

it('returns 404 when user not found', function () {
    $response = $this->getJson('/api/users/9999');

    $response->assertNotFound()->assertJson(['message' => 'Resource not found.']);
});

it('can update a user', function () {
    $user = User::factory()->create(['name' => 'Old']);

    $response = $this->putJson("/api/users/{$user->id}", ['name' => 'New']);

    $response->assertOk()
        ->assertJsonFragment(['name' => 'New']);

    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New']);
});

it('can delete a user', function () {
    $user = User::factory()->create();

    $response = $this->deleteJson("/api/users/{$user->id}");

    $response->assertNoContent();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
