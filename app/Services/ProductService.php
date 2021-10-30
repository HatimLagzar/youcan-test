<?php

namespace App\Services;

use App\Exceptions\DatabaseManipulationException;
use App\Exceptions\ImageValidationException;
use App\Exceptions\ValidationException;
use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use stdClass;

class ProductService
{
    protected ProductRepository $productRepository;
    protected CategoryService $categoryService;
    protected ProductCategoryService $productCategoryService;
    protected ProductValidator $productValidator;

    public function __construct(
        ProductRepository      $productRepository,
        CategoryService        $categoryService,
        ProductCategoryService $productCategoryService,
        ProductValidator       $productValidator
    )
    {
        $this->productRepository = $productRepository;
        $this->productCategoryService = $productCategoryService;
        $this->categoryService = $categoryService;
        $this->productValidator = $productValidator;
    }

    public function getAll(array $columns = []): Collection
    {
        return $this->productRepository->getAll($columns);
    }

    public function getAllPaginated(): LengthAwarePaginator
    {
        return $this->productRepository->getAllPaginated();
    }

    public function findByName(string $name): ?stdClass
    {
        return $this->productRepository->findByName($name);
    }

    private function sanitizeInputs(array $inputs): array
    {
        $inputs['name'] = filter_var($inputs['name'], FILTER_SANITIZE_STRING);
        $inputs['description'] = filter_var($inputs['description'], FILTER_SANITIZE_STRING);
        $inputs['price'] = filter_var($inputs['price'], FILTER_SANITIZE_NUMBER_FLOAT);
        $inputs['categories'] = $inputs['categories'] ?? [];
        $inputs['categories'] = filter_var_array($inputs['categories'], FILTER_SANITIZE_NUMBER_INT);

        return $inputs;
    }

    /**
     * @throws DatabaseManipulationException
     * @throws ValidationException
     * @throws ImageValidationException
     */
    public function create(array $inputs): ?stdClass
    {
        $this->productValidator->validate($inputs);
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
            throw new DatabaseManipulationException('Unknown error occurred while saving the product.');
        }

        $this->productCategoryService->createProductCategories($inputs['categories'], $product->id);

        return $product;
    }
}
