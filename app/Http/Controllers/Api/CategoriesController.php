<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function all()
    {
        return response([
            'status' => 200,
            'msg' => 'Categories pulled successfully.',
            'categories' => $this->categoryService->getAll()
        ]);
    }
}
