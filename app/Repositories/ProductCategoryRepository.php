<?php

namespace App\Repositories;

use App\Models\ProductCategory;

class ProductCategoryRepository
{
    protected ProductCategory $productCategory;

    public function __construct(ProductCategory $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    public function store(int $categoryId, int $productId)
    {
        return $this->productCategory->create([
            'category_id' => $categoryId,
            'product_id' => $productId,
        ]);
    }
}
