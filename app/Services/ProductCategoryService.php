<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductCategoryRepository;
use Exception;

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

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }

    public function findById(int $id)
    {
        return $this->categoryRepository->findById($id);
    }

    /**
     * @throws Exception
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
                    throw new Exception('Unknown error occurred while saving categories, retry later or contact the support.', 500);
                }
            }
        }
    }
}
