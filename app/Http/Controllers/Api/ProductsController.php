<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DatabaseManipulationException;
use App\Exceptions\ImageValidationException;
use App\Exceptions\ValidationException;
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
        try {
            $product = $this->productService->create($request->all());
            return response([
                'status' => 200,
                'msg' => 'Product created successfully.',
                'product' => $product
            ]);
        } catch (ValidationException | ImageValidationException | DatabaseManipulationException $exception) {
            return response([
                'status' => $exception->getCode(),
                'msg' => $exception->getMessage()
            ]);
        }
    }
}
