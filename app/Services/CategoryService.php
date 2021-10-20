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

    public function getAll(array $columns = [])
    {
        return $this->categoryRepository->getAll($columns);
    }

    public function findById(int $id)
    {
        return $this->categoryRepository->findById($id);
    }

    public function findByName(string $name)
    {
        return $this->categoryRepository->findByName($name);
    }

    public function create(string $name, $parentId)
    {
        return $this->categoryRepository->create([
            'name' => $name,
            'parent_id' => $parentId
        ]);
    }

    public function createProductCategory(int $categoryId, int $productId): bool
    {
        return $this->productCategory->store($categoryId, $productId);
    }
}
