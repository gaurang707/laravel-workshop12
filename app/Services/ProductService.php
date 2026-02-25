<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    /**
     * Retrieve all products, optionally only active ones.
     *
     * @return Collection<int, Product>
     */
    public function list(?bool $onlyActive = null): Collection
    {
        $query = Product::query();

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        return $query->get();
    }

    /**
     * Create a new product with provided attributes.
     *
     * @param  array<string, mixed>  $attrs
     */
    public function create(array $attrs): Product
    {
        return Product::create($attrs);
    }

    /**
     * Update the given product with attributes.
     *
     * @param  array<string, mixed>  $attrs
     */
    public function update(Product $product, array $attrs): Product
    {
        $product->fill($attrs);
        $product->save();

        return $product;
    }

    /**
     * Delete the given product.
     */
    public function delete(Product $product): void
    {
        $product->delete();
    }
}
