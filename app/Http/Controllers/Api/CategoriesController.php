<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductCategoryService;

class CategoriesController extends Controller
{
    protected CategoryService $categoryService;
    protected ProductCategoryService $productCategoryService;

    public function __construct(
        CategoryService        $categoryService,
        ProductCategoryService $productCategoryService
    )
    {
        $this->categoryService = $categoryService;
        $this->productCategoryService = $productCategoryService;
    }

    public function all()
    {
        return response([
            'status' => 200,
            'msg' => 'Categories pulled successfully.',
            'categories' => $this->categoryService->getAll()
        ]);
    }

    public function productsCategories()
    {
        return response([
            'status' => 200,
            'msg' => 'Products Categories pulled successfully.',
            'productsCategories' => $this->productCategoryService->getAllProductsCategories()
        ]);
    }
}
