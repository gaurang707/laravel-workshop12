<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list products', function () {
    Product::factory()->count(3)->create();

    $response = $this->getJson('/api/products');

    $response->assertOk()
        ->assertJsonCount(3);
});

it('can create a product', function () {
    $payload = [
        'name' => 'Test product',
        'price' => 12.34,
        'is_active' => true,
    ];

    $response = $this->postJson('/api/products', $payload);

    $response->assertCreated()
        ->assertJsonFragment(['name' => 'Test product']);

    $this->assertDatabaseHas('products', ['name' => 'Test product']);
});

it('validates input when creating', function () {
    $response = $this->postJson('/api/products', []);

    $response->assertStatus(422);
});

it('can show a product', function () {
    $product = Product::factory()->create();

    $response = $this->getJson("/api/products/{$product->id}");

    $response->assertOk()
        ->assertJsonFragment(['id' => $product->id]);
});

it('can update a product', function () {
    $product = Product::factory()->create(['name' => 'Old']);

    $response = $this->putJson("/api/products/{$product->id}", ['name' => 'New']);

    $response->assertOk()
        ->assertJsonFragment(['name' => 'New']);

    $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New']);
});

it('can delete a product', function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson("/api/products/{$product->id}");

    $response->assertNoContent();

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});
