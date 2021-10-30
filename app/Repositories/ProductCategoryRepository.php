<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use Illuminate\Support\Collection;

class ProductCategoryRepository
{
    protected ProductCategory $productCategory;

    public function __construct(ProductCategory $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    public function getAll(): Collection
    {
        return $this->productCategory->all();
    }

    public function store(int $categoryId, int $productId)
    {
        return $this->productCategory->create([
            'category_id' => $categoryId,
            'product_id' => $productId,
        ]);
    }
}
