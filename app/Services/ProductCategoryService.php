<?php

namespace App\Services;

use App\Exceptions\DatabaseManipulationException;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductCategoryRepository;

class ProductCategoryService
{
    protected CategoryRepository $categoryRepository;
    protected ProductCategoryRepository $productCategoryRepository;

    public function __construct(
        CategoryRepository        $categoryRepository,
        ProductCategoryRepository $productCategoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    public function getAllProductsCategories()
    {
        return $this->productCategoryRepository->getAll();
    }

    /**
     * @throws DatabaseManipulationException
     */
    public function createProductCategories(array $categories, int $productId)
    {
        foreach ($categories as $categoryId) {
            $category = $this->categoryRepository->findById($categoryId);
            if ($category) {
                $productCategory = $this->productCategoryRepository->store(
                    $category->id,
                    $productId
                );

                if (!$productCategory) {
                    throw new DatabaseManipulationException('Unknown error occurred while saving categories, retry later or contact the support.');
                }
            }
        }
    }
}
