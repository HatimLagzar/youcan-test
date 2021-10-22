<?php

namespace App\Services;

use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Support\Facades\Validator;

class ProductService
{
    protected ProductRepository $productRepository;
    protected ProductCategoryRepository $productCategory;
    protected CategoryService $categoryService;

    public function __construct(
        ProductRepository         $productRepository,
        ProductCategoryRepository $productCategoryRepository,
        CategoryService           $categoryService
    )
    {
        $this->productRepository = $productRepository;
        $this->productCategory = $productCategoryRepository;
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

    /**
     * @throws Exception
     */
    public function create(array $inputs)
    {
        $validation = Validator::make($inputs, [
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
            'categories.*' => 'numeric|nullable'
        ]);

        $image = $inputs['image'];
        if (!is_string($image)) {
            $imageValidation = Validator::make(['image' => $image], [
                'image' => 'image|max:10000|required',
            ]);

            if ($imageValidation->fails()) {
                throw new Exception($imageValidation->errors()->first(), 400);
            }
        }

        if ($validation->fails()) {
            throw new Exception($validation->errors()->first(), 400);
        }

        $name = filter_var($inputs['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($inputs['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($inputs['price'], FILTER_SANITIZE_NUMBER_FLOAT);
        $price = floatval($price);
        $categories = $inputs['categories'] ?? [];
        $categories = filter_var_array($categories, FILTER_SANITIZE_NUMBER_INT);
        $image = $inputs['image'];
        $imageSrc = is_string($image) ? $image : $image->hashName();
        if (!is_string($image)) {
            $image->storeAs('public/products/', $imageSrc);
        }

        $product = $this->productRepository->store([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image_src' => $imageSrc
        ]);

        if (!$product) {
            throw new Exception('Unknown error occurred while saving the product.', 500);
        }

        foreach ($categories as $categoryId) {
            $category = $this->categoryService->findById($categoryId);
            if ($category) {
                $productCategory = $this->productCategory->store(
                    $category->id,
                    $product->id
                );

                if (!$productCategory) {
                    throw new Exception('Unknown error occurred while saving categories, retry later or contact the support.', 500);
                }
            }
        }

        return $product;
    }
}
