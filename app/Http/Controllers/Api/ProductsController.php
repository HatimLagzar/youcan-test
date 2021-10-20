<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function all()
    {
        return response([
            'status' => 200,
            'msg' => 'Products pulled successfully.',
            'products' => $this->productService->getAllPaginated()
        ]);
    }

    public function store(Request $request)
    {
        return $this->productService->create($request->all());
    }
}
