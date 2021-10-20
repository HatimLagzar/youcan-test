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

    public function store(int $categoryId, int $productId): bool
    {
        $productCategory = new ProductCategory();
        $productCategory->category_id = $categoryId;
        $productCategory->product_id = $productId;
        if (!$productCategory->save()) {
            return false;
        }

        return true;
    }
}
