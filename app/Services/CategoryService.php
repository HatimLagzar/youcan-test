<?php

namespace App\Services;

use App\Exceptions\DatabaseManipulationException;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductCategoryRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use stdClass;

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

    public function getAll(array $columns = ['*']): Collection
    {
        return $this->categoryRepository->getAll($columns);
    }

    public function getAllNamesAsArray(): array
    {
        return $this->getAll(['name'])
            ->transform(function ($category) {
                return $category->name;
            })
            ->toArray();
    }

    public function findById(int $id): ?stdClass
    {
        return $this->categoryRepository->findById($id);
    }

    public function findByName(string $name): ?stdClass
    {
        return $this->categoryRepository->findByName($name);
    }

    /**
     * @throws DatabaseManipulationException
     */
    public function create(string $name, $parentId): stdClass
    {
        $category = $this->categoryRepository->create([
            'name' => $name,
            'parent_id' => $parentId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if (!$category) {
            throw new DatabaseManipulationException('Unknown error occured while creating a category.');
        }

        return $category;
    }

    public function createProductCategory(int $categoryId, int $productId): stdClass
    {
        return $this->productCategory->store($categoryId, $productId);
    }

    /**
     * @param array $categoriesNames
     * @return array
     */
    public function getIdsFromNames(array $categoriesNames): array
    {
        $categoriesIds = array_map(function ($name) {
            $category = $this->categoryRepository->findByName($name);
            if ($category) {
                return $category->id;
            }

            return null;
        }, $categoriesNames);

        return Arr::where($categoriesIds, function ($id) {
            return !empty($id);
        });
    }
}
