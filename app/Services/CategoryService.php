<?php

namespace App\Services;

use App\Exceptions\DatabaseManipulationException;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductCategoryRepository;
use Illuminate\Support\Collection;

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

    public function getAll(array $columns = []): Collection
    {
        return $this->categoryRepository->getAll($columns);
    }

    public function findById(int $id): ?Category
    {
        return $this->categoryRepository->findById($id);
    }

    public function findByName(string $name): ?Category
    {
        return $this->categoryRepository->findByName($name);
    }

    /**
     * @throws DatabaseManipulationException
     */
    public function create(string $name, $parentId): Category
    {
        $category = $this->categoryRepository->create([
            'name' => $name,
            'parent_id' => $parentId
        ]);

        if (!$category) {
            throw new DatabaseManipulationException('Unknown error occured while creating a category.');
        }

        return $category;
    }

    public function createProductCategory(int $categoryId, int $productId): bool
    {
        return $this->productCategory->store($categoryId, $productId);
    }
}
