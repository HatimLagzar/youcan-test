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

    public function getAll(array $columns = [])
    {
        return $this->product->with(['categories'])->select(...$columns)->get();
    }

    public function getAllPaginated()
    {
        return $this->product->with(['categories'])->paginate(3);
    }

    public function findByName(string $name)
    {
        return $this->product->where('name', '=', $name)->first();
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
