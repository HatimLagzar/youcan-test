<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function all(Request $request)
    {
        return response([
            'status' => 200,
            'msg' => 'Products pulled successfully.',
            'products' => $this->productService->getAllPaginated($request)
        ]);
    }

    public function store(Request $request)
    {
        try {
            $product = $this->productService->create($request->all());
            return response([
                'status' => 200,
                'msg' => 'Product created successfully.',
                'product' => $product
            ]);
        } catch (Exception $exception) {
            return response([
                'status' => $exception->getCode(),
                'msg' => $exception->getMessage()
            ]);
        }
    }
}
