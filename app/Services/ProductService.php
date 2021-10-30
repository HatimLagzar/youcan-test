<?php

namespace App\Services;

use App\Exceptions\DatabaseManipulationException;
use App\Exceptions\ValidationException;
use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
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

    public function getAll(array $columns = ['*']): Collection
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
        $inputs['price'] = filter_var(number_format($inputs['price'], 2), FILTER_SANITIZE_NUMBER_FLOAT);
        $inputs['categories'] = $inputs['categories'] ?? [];
        $inputs['categories'] = filter_var_array($inputs['categories'], FILTER_SANITIZE_NUMBER_INT);

        return $inputs;
    }

    /**
     * @throws DatabaseManipulationException
     * @throws ValidationException
     */
    public function create(array $inputs): stdClass
    {
        $this->productValidator->validate($inputs);
        $inputs = $this->sanitizeInputs($inputs);
        $imageSrc = $this->uploadThumbnail($inputs['image']);

        $product = $this->productRepository->store([
            'name' => $inputs['name'],
            'description' => $inputs['description'],
            'price' => $inputs['price'],
            'image_src' => $imageSrc,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if (!$product) {
            throw new DatabaseManipulationException('Unknown error occurred while saving the product.');
        }

        $this->productCategoryService->createProductCategories($inputs['categories'], $product->id);

        return $product;
    }

    /**
     * @param $image UploadedFile|File the file that we want to upload
     * @return string the uploaded file name
     */
    public function uploadThumbnail($image): string
    {
        $fileName = $image->hashName();
        Storage::putFileAs('public/products/', $image, $fileName);
        $this->deleteTemporaryFile($image);
        return $fileName;
    }

    /**
     * @param $image
     */
    public function deleteTemporaryFile($image): void
    {
        if (Storage::exists('public/tmp_files/' . $image->getBasename())) {
            Storage::delete('public/tmp_files/' . $image->getBasename());
        }
    }
}
