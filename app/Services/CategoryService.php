<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductCategoryRepository;

class CategoryService
{
    protected CategoryRepository $categoryRepository;
    protected ProductCategoryRepository $productCategory;

    public function __construct(
        CategoryRepository        $categoryRepository,
        ProductCategoryRepository $productCategoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->productCategory = $productCategoryRepository;
    }

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }

    public function findById(int $id)
    {
        return $this->categoryRepository->findById($id);
    }

    public function create(int $categoryId, int $productId): bool
    {
        $category = $this->findById($categoryId);
        if ($category) {
            $isSaved = $this->createProductCategory(
                $category->id,
                $productId
            );

            if (!$isSaved) {
                return false;
            }
        }

        return true;
    }

    public function createProductCategory(int $categoryId, int $productId): bool
    {
        return $this->productCategory->store($categoryId, $productId);
    }
}
