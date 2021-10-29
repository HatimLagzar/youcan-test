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

    public function findByName(string $name): ?Product
    {
        return $this->product->where('name', '=', $name)->first();
    }

    public function store(array $inputs)
    {
        return $this->product->create($inputs);
    }
}
