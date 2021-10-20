<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAll()
    {
        return $this->product->with(['categories'])->get();
    }

    public function getAllPaginated()
    {
        return $this->product->with(['categories'])->paginate(3);
    }

    public function store(array $inputs)
    {
        $product = new Product();
        $product->name = $inputs['name'];
        $product->description = $inputs['description'];
        $product->price = $inputs['price'];
        $product->image_src = $inputs['image_src'];

        if (!$product->save()) {
            return false;
        }

        return $product;
    }
}
