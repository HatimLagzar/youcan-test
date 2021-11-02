<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Models\ProductCategory;

class ProductCategoryRepository
{
    public function getAll(): Collection
    {
        return ProductCategory::all();
    }

    public function create(int $categoryId, int $productId): ?int
    {
        return ProductCategory::create([
            'category_id' => $categoryId,
            'product_id' => $productId,
        ]);
    }
}
