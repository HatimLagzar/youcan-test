<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository
{
    public function getAll(array $columns = ['*']): Collection
    {
        return Product::all($columns);
    }

    public function getAllPaginated(): LengthAwarePaginator
    {
        return Product::paginate(3);
    }

    /**
     * @param string $name
     * @return Product|null
     */
    public function findByName(string $name): ?Product
    {
        return Product::where('name', '=', $name)
            ->get()
            ->first();
    }

    /**
     * @param array $inputs
     * @return Product|null
     */
    public function create(array $inputs): ?Product
    {
        return Product::create($inputs);
    }

    public function delete(int $id): int
    {
        return Product::destroy($id);
    }
}
