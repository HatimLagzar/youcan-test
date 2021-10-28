<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;
use Exception;

class ProductService
{
    protected ProductRepository $productRepository;
    protected CategoryService $categoryService;
    protected ProductCategoryService $productCategoryService;

    public function __construct(
        ProductRepository      $productRepository,
        CategoryService        $categoryService,
        ProductCategoryService $productCategoryService
    )
    {
        $this->productRepository = $productRepository;
        $this->productCategoryService = $productCategoryService;
        $this->categoryService = $categoryService;
    }

    public function getAll(array $columns = [])
    {
        return $this->productRepository->getAll($columns);
    }

    public function getAllPaginated($request)
    {
        return $this->productRepository->getAllPaginated($request);
    }

    public function findByName(string $name)
    {
        return $this->productRepository->findByName($name);
    }

    public function sanitizeInputs(array $inputs): array
    {
        $inputs['name'] = filter_var($inputs['name'], FILTER_SANITIZE_STRING);
        $inputs['description'] = filter_var($inputs['description'], FILTER_SANITIZE_STRING);
        $inputs['price'] = filter_var($inputs['price'], FILTER_SANITIZE_NUMBER_FLOAT);
        $inputs['price'] = floatval($inputs['price']);
        $inputs['categories'] = $inputs['categories'] ?? [];
        $inputs['categories'] = filter_var_array($inputs['categories'], FILTER_SANITIZE_NUMBER_INT);

        return $inputs;
    }

    /**
     * @throws Exception
     */
    public function create(array $inputs)
    {
        ProductValidator::validate($inputs);
        $inputs = $this->sanitizeInputs($inputs);

        $imageSrc = is_string($inputs['image']) ? $inputs['image'] : $inputs['image']->hashName();
        if (!is_string($inputs['image'])) {
            $inputs['image']->storeAs('public/products/', $imageSrc);
        }

        $product = $this->productRepository->store([
            'name' => $inputs['name'],
            'description' => $inputs['description'],
            'price' => $inputs['price'],
            'image_src' => $imageSrc
        ]);

        if (!$product) {
            throw new Exception('Unknown error occurred while saving the product.', 500);
        }

        $this->productCategoryService->createProductCategories($inputs['categories'], $product->id);

        return $product;
    }
}
